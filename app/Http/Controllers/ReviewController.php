<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $product->reviews()->create($request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:5000'],
            'author' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
        ]));

        return redirect(route('product', $product).'#reviews')->with('status', 'Thank you for your review!');
    }
}
