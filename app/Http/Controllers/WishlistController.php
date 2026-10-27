<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $ids = $request->session()->get('wishlist', []);

        return view('wishlist.index', [
            'products' => Product::with('category')->whereIn('id', $ids)->get()->sortBy(fn (Product $product) => array_search($product->id, $ids))->values(),
            'cartIds' => array_keys($request->session()->get('cart', [])),
        ]);
    }

    /**
     * Add the product to the session wishlist, or remove it if it is already there.
     */
    public function toggle(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $ids = $request->session()->get('wishlist', []);
        $saved = ! in_array($product->id, $ids, true);

        $ids = $saved ? [...$ids, $product->id] : array_values(array_diff($ids, [$product->id]));
        $request->session()->put('wishlist', $ids);

        $message = $saved ? "{$product->name} was added to your favorites." : "{$product->name} was removed from your favorites.";

        if ($request->expectsJson()) {
            return response()->json(['saved' => $saved, 'count' => count($ids), 'message' => $message]);
        }

        return back()->with('status', $message);
    }
}
