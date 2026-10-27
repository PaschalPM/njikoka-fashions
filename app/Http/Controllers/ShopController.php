<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when($request->string('q')->trim()->value(), fn ($query, $term) => $query->where('name', 'like', "%{$term}%"))
            ->when($request->input('stock') === 'sale', fn ($query) => $query->where('in_stock', true))
            ->when($request->input('stock') === 'sold', fn ($query) => $query->where('in_stock', false))
            ->when($request->array('categories'), fn ($query, $slugs) => $query->whereHas('category', fn ($q) => $q->whereIn('slug', $slugs)))
            ->orderBy('name')
            ->paginate(16)
            ->withQueryString();

        return view('shop.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        return view('shop.show', [
            'product' => $product->load('category', 'reviews'),
        ]);
    }
}
