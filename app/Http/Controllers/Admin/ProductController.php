<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when($request->string('q')->trim()->value(), fn ($query, $term) => $query->where('name', 'like', "%{$term}%"))
            ->when($request->integer('category'), fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->when($request->input('stock') === 'in', fn ($query) => $query->where('in_stock', true))
            ->when($request->input('stock') === 'out', fn ($query) => $query->where('in_stock', false))
            ->latest('id')
            ->paginate(24)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'totals' => [
                'all' => Product::count(),
                'in_stock' => Product::where('in_stock', true)->count(),
                'sold_out' => Product::where('in_stock', false)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['in_stock' => true]),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: true);
        $data['slug'] = $this->uniqueSlug($data['name']);

        $product = Product::create($data);

        return redirect()->route('admin.products.index')->with('status', "{$product->name} was added to the shop.");
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, imageRequired: false);
        $previousImage = $product->image;

        $product->update($data);

        if (isset($data['image'])) {
            $this->deleteUploadedImage($previousImage);
        }

        return redirect()->route('admin.products.index')->with('status', "{$product->name} was updated.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        $this->deleteUploadedImage($product->image);

        return redirect()->route('admin.products.index')->with('status', "{$product->name} was deleted.");
    }

    /**
     * Validate the form and convert it to product attributes (price in cents, image as a public path).
     *
     * @return array{category_id: int, name: string, summary: ?string, description: ?string, price_cents: int, in_stock: bool, image?: string}
     */
    private function validated(Request $request, bool $imageRequired): array
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'price' => ['required', 'decimal:0,2', 'min:0.5', 'max:100000'],
            'summary' => ['nullable', 'string', 'max:5000'],
            'description' => ['nullable', 'string', 'max:20000'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $data = [
            'category_id' => (int) $input['category_id'],
            'name' => $input['name'],
            'summary' => $input['summary'] ?? null,
            'description' => $input['description'] ?? null,
            'price_cents' => (int) round($input['price'] * 100),
            'in_stock' => $request->boolean('in_stock'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = 'storage/'.$request->file('image')->store('products', 'public');
        }

        return $data;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;

        for ($suffix = 2; Product::where('slug', $slug)->exists(); $suffix++) {
            $slug = "{$base}-{$suffix}";
        }

        return $slug;
    }

    /**
     * Remove an image uploaded through the admin. Bundled images under public/images are left alone.
     */
    private function deleteUploadedImage(?string $image): void
    {
        if ($image && Str::startsWith($image, 'storage/')) {
            Storage::disk('public')->delete(Str::after($image, 'storage/'));
        }
    }
}
