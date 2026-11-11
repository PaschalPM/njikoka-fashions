<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $styles = config('store.styles');
        $term = $request->string('q')->trim()->value();
        $selectedStyles = array_values(array_intersect($request->array('styles'), array_keys($styles)));

        // A search like "baban-riga" or "george" is treated as picking that style rather than as free text.
        if ($term !== '' && ($style = $this->styleForSearch($term, $styles))) {
            $selectedStyles = array_values(array_unique([...$selectedStyles, $style]));
            $term = '';
        }

        $products = Product::query()
            ->with('category')
            ->when($term, fn ($query, $term) => $query->where(fn ($match) => $match
                ->where('name', 'like', "%{$term}%")
                ->orWhere('summary', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")))
            ->when($selectedStyles, fn ($query) => $query->where(fn ($match) => collect($selectedStyles)
                ->flatMap(fn (string $style) => $styles[$style]['keywords'])
                ->each(fn (string $keyword) => $match->orWhere('name', 'like', "%{$keyword}%")->orWhere('summary', 'like', "%{$keyword}%"))))
            ->when($request->input('stock') === 'sale', fn ($query) => $query->where('in_stock', true))
            ->when($request->input('stock') === 'sold', fn ($query) => $query->where('in_stock', false))
            ->when($request->array('categories'), fn ($query, $slugs) => $query->whereHas('category', fn ($q) => $q->whereIn('slug', $slugs)))
            ->orderBy('name')
            ->paginate(16)
            ->withQueryString();

        return view('shop.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'styleCounts' => $this->styleCounts($styles),
            'selectedStyles' => $selectedStyles,
        ]);
    }

    /**
     * How many products match each style, leaving out styles with none so the filter never leads to an empty page.
     *
     * @param  array<string, array{label: string, keywords: list<string>, aliases: list<string>}>  $styles
     * @return array<string, int>
     */
    private function styleCounts(array $styles): array
    {
        $catalogue = Product::query()->get(['name', 'summary'])
            ->map(fn (Product $product) => Str::lower($product->name.' '.$product->summary));

        return collect($styles)
            ->map(fn (array $style) => $catalogue->filter(fn (string $text) => Str::contains($text, $style['keywords']))->count())
            ->filter()
            ->all();
    }

    /**
     * Match a search to a style by its label, keywords or aliases, ignoring case, hyphens and extra spaces.
     *
     * @param  array<string, array{label: string, keywords: list<string>, aliases: list<string>}>  $styles
     */
    private function styleForSearch(string $term, array $styles): ?string
    {
        $normalise = fn (string $text) => Str::of($text)->lower()->replaceMatches('/[-_]+/', ' ')->squish()->value();
        $needle = $normalise($term);

        return collect($styles)->search(fn (array $style) => collect([$style['label'], ...$style['keywords'], ...$style['aliases']])
            ->map($normalise)
            ->contains($needle)) ?: null;
    }

    public function show(Product $product): View
    {
        return view('shop.show', [
            'product' => $product->load('category', 'reviews'),
        ]);
    }
}
