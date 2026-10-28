<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WhatsappContact;
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
            'whatsappOrderLinks' => $this->whatsappOrderLinks($items),
        ]);
    }

    /**
     * One link per admin-listed WhatsApp contact; the customer picks who follows up on the order.
     *
     * @param  Collection<int, object{product: Product, quantity: int, subtotal_cents: int}>  $items
     * @return Collection<int, object{contact: WhatsappContact, url: string}>
     */
    private function whatsappOrderLinks(Collection $items): Collection
    {
        if ($items->isEmpty()) {
            return collect();
        }

        return WhatsappContact::forStorefront()
            ->map(fn (WhatsappContact $contact) => (object) ['contact' => $contact, 'url' => route('whatsapp.order', $contact->number)]);
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
