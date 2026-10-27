<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $quantities = $request->session()->get('cart', []);

        $items = Product::with('category')->whereIn('id', array_keys($quantities))->get()
            ->map(fn (Product $product) => (object) [
                'product' => $product,
                'quantity' => $quantities[$product->id],
                'subtotal_cents' => $product->price_cents * $quantities[$product->id],
            ]);

        $favoriteIds = array_diff($request->session()->get('wishlist', []), array_keys($quantities));

        return view('cart.index', [
            'items' => $items,
            'item_count' => $items->sum('quantity'),
            'total_cents' => $items->sum('subtotal_cents'),
            'favorites' => Product::whereIn('id', $favoriteIds)->where('in_stock', true)->take(4)->get(),
            'newInStore' => $items->isEmpty() ? Product::where('in_stock', true)->latest('id')->take(4)->get() : collect(),
            'whatsappOrderUrl' => $this->whatsappOrderUrl($items),
        ]);
    }

    /**
     * Link that opens WhatsApp with the cart written out, so the store can follow up on the order in chat.
     *
     * @param  Collection<int, object{product: Product, quantity: int, subtotal_cents: int}>  $items
     */
    private function whatsappOrderUrl(Collection $items): ?string
    {
        if ($items->isEmpty() || ! config('store.whatsapp')) {
            return null;
        }

        $lines = $items->map(fn ($item) => "• {$item->product->name} × {$item->quantity}: $".number_format($item->subtotal_cents / 100, 2)
            ."\n  ".route('product', $item->product));

        $message = "Hi Njikoka Fashions! I'd like to order:\n\n"
            .$lines->implode("\n")
            ."\n\nTotal: $".number_format($items->sum('subtotal_cents') / 100, 2)
            ."\n\nCould you help me complete this order?";

        return 'https://wa.me/'.config('store.whatsapp').'?text='.rawurlencode($message);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->in_stock, 422, 'This item is sold out.');

        $quantity = $request->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:20']])['quantity'] ?? 1;

        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min(20, ($cart[$product->id] ?? 0) + $quantity);
        $request->session()->put('cart', $cart);

        return back()->with('status', "{$product->name} was added to your cart.");
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $quantity = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:20']])['quantity'];

        $cart = $request->session()->get('cart', []);

        if ($quantity === 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = $quantity;
        }

        $request->session()->put('cart', $cart);

        return back();
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $request->session()->forget("cart.{$product->id}");

        return back()->with('status', "{$product->name} was removed from your cart.");
    }
}
