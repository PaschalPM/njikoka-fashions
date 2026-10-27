<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Exception\ExceptionInterface as StripeException;
use Stripe\StripeClient;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $items = $this->cartItems($request);

        if ($items->isEmpty()) {
            return redirect()->route('cart');
        }

        return view('checkout.create', [
            'items' => $items,
            'total_cents' => $items->sum('subtotal_cents'),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request, StripeClient $stripe): RedirectResponse
    {
        $items = $this->cartItems($request);

        if ($items->isEmpty()) {
            return redirect()->route('cart');
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postcode' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'size:2'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $order = DB::transaction(function () use ($request, $data, $items) {
            $order = Order::create([
                ...$data,
                'user_id' => $request->user()?->id,
                'total_cents' => $items->sum('subtotal_cents'),
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item->product->id,
                    'name' => $item->product->name,
                    'price_cents' => $item->product->price_cents,
                    'quantity' => $item->quantity,
                ]);
            }

            return $order;
        });

        try {
            $session = $this->createStripeSession($stripe, $order, $items);
        } catch (StripeException $e) {
            Log::error('Stripe checkout session failed', ['order' => $order->number, 'error' => $e->getMessage()]);
            $order->update(['status' => 'failed']);

            return back()->withInput()->withErrors(['payment' => 'We could not start the payment. Please try again or contact us.']);
        }

        $order->update(['stripe_session_id' => $session->id]);

        return redirect()->away($session->url);
    }

    /**
     * @param  Collection<int, object{product: Product, quantity: int, subtotal_cents: int}>  $items
     */
    private function createStripeSession(StripeClient $stripe, Order $order, Collection $items): Session
    {
        return $stripe->checkout->sessions->create([
            'mode' => 'payment',
            'customer_email' => $order->email,
            'client_reference_id' => $order->number,
            'metadata' => ['order_number' => $order->number],
            'line_items' => $items->map(fn ($item) => [
                'quantity' => $item->quantity,
                'price_data' => [
                    'currency' => config('store.currency'),
                    'unit_amount' => $item->product->price_cents,
                    'product_data' => [
                        'name' => $item->product->name,
                        'images' => [asset($item->product->image)],
                    ],
                ],
            ])->values()->all(),
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', $order),
        ]);
    }

    public function success(Request $request, StripeClient $stripe): View|RedirectResponse
    {
        $order = Order::where('stripe_session_id', $request->string('session_id'))->first();

        if (! $order) {
            return redirect()->route('cart');
        }

        $session = $stripe->checkout->sessions->retrieve($order->stripe_session_id);

        if ($session->payment_status === 'paid') {
            $order->markPaid($session->payment_intent);
            $request->session()->forget('cart');
        }

        return view('checkout.success', ['order' => $order->load('items')]);
    }

    public function cancel(Order $order): RedirectResponse
    {
        if (! $order->isPaid()) {
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->route('checkout')->with('status', 'Payment was cancelled. Your cart has been kept so you can try again.');
    }

    /**
     * Resolve the session cart into products with quantities and subtotals.
     *
     * @return Collection<int, object{product: Product, quantity: int, subtotal_cents: int}>
     */
    private function cartItems(Request $request): Collection
    {
        $quantities = $request->session()->get('cart', []);

        return Product::whereIn('id', array_keys($quantities))->where('in_stock', true)->get()
            ->map(fn (Product $product) => (object) [
                'product' => $product,
                'quantity' => $quantities[$product->id],
                'subtotal_cents' => $product->price_cents * $quantities[$product->id],
            ]);
    }
}
