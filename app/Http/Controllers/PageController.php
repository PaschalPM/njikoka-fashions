<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'categories' => Category::orderBy('name')->get()->each(fn (Category $category) => $category->setRelation(
                'products', $category->products()->latest('id')->get(),
            )),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function faqs(): View
    {
        return view('pages.faqs');
    }

    public function shipping(): View
    {
        return view('pages.shipping');
    }

    public function returns(): View
    {
        return view('pages.returns');
    }

    public function customDesigns(): View
    {
        return view('pages.custom-designs');
    }

    public function bookAppointment(): View
    {
        return view('pages.book-appointment');
    }
}
