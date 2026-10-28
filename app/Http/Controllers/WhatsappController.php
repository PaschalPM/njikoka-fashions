<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WhatsappClick;
use App\Models\WhatsappContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Every WhatsApp link in the shop goes through here, so the admin can see who reached out and to whom.
 */
class WhatsappController extends Controller
{
    /**
     * Visitor cookie that groups one person's clicks, kept for a year.
     */
    private const VISITOR_COOKIE = 'wa_visitor';

    /**
     * "Chat with us": a greeting that names the product when the visitor came from a product page.
     */
    public function chat(Request $request, string $number): RedirectResponse
    {
        $contact = $this->contact($number);
        $product = $request->filled('product') ? Product::firstWhere('slug', $request->query('product')) : null;

        $greeting = $product
            ? "Hi Njikoka Fashions! I'm interested in {$product->name}: ".route('product', $product)
            : 'Hi Njikoka Fashions! I have a question.';

        return $this->open($request, $contact, $greeting, ['source' => 'chat', 'product_id' => $product?->id]);
    }

    /**
     * "Order via WhatsApp": the cart written out so the store can finish the order in chat.
     */
    public function order(Request $request, string $number): RedirectResponse
    {
        $contact = $this->contact($number);
        $items = $this->cartItems($request);

        if ($items->isEmpty()) {
            return redirect()->route('cart');
        }

        $lines = $items->map(fn ($item) => "• {$item->product->name} × {$item->quantity}: $".number_format($item->subtotal_cents / 100, 2)
            ."\n  ".route('product', $item->product));

        $message = "Hi Njikoka Fashions! I'd like to order:\n\n"
            .$lines->implode("\n")
            ."\n\nTotal: $".number_format($items->sum('subtotal_cents') / 100, 2)
            ."\n\nCould you help me complete this order?";

        return $this->open($request, $contact, $message, [
            'source' => 'order',
            'cart_items' => $items->sum('quantity'),
            'cart_total_cents' => $items->sum('subtotal_cents'),
        ]);
    }

    /**
     * Only numbers currently offered in the shop can be opened, so the route can't be used to log or redirect to anyone else.
     */
    private function contact(string $number): WhatsappContact
    {
        return WhatsappContact::forStorefront()->firstWhere('number', $number) ?? abort(404);
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function open(Request $request, WhatsappContact $contact, string $message, array $details): RedirectResponse
    {
        $visitor = Str::isUuid($request->cookie(self::VISITOR_COOKIE)) ? $request->cookie(self::VISITOR_COOKIE) : (string) Str::uuid();

        WhatsappClick::create([
            'contact_label' => $contact->label,
            'contact_number' => $contact->number,
            'visitor' => $visitor,
            'user_id' => $request->user()?->id,
            ...$details,
        ]);

        return redirect()->away($contact->chatUrl($message))
            ->withCookie(cookie(self::VISITOR_COOKIE, $visitor, 60 * 24 * 365));
    }

    /**
     * Resolve the session cart into products with quantities and subtotals.
     *
     * @return Collection<int, object{product: Product, quantity: int, subtotal_cents: int}>
     */
    private function cartItems(Request $request): Collection
    {
        $quantities = $request->session()->get('cart', []);

        return Product::whereIn('id', array_keys($quantities))->get()
            ->map(fn (Product $product) => (object) [
                'product' => $product,
                'quantity' => $quantities[$product->id],
                'subtotal_cents' => $product->price_cents * $quantities[$product->id],
            ]);
    }
}
