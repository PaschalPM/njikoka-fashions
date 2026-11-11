<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
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
            'ngnPerUsd' => Setting::ngnPerUsd(),
        ]);
    }

    /**
     * Save a new naira-to-dollar rate and reprice every product's card checkout amount from its naira price.
     */
    public function updateExchangeRate(Request $request): RedirectResponse
    {
        $rate = (int) $request->validate(['ngn_per_usd' => ['required', 'integer', 'min:100', 'max:100000']], [], ['ngn_per_usd' => 'exchange rate'])['ngn_per_usd'];

        DB::transaction(function () use ($rate) {
            Setting::setNgnPerUsd($rate);

            Product::query()->each(fn (Product $product) => $product->update(['price_cents' => Product::usdCentsFor($product->price_naira, $rate)]));
        });

        return back()->with('status', 'Exchange rate saved: ₦'.number_format($rate).' = $1. Dollar prices were updated.');
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(['in_stock' => true]),
            'categories' => Category::orderBy('name')->get(),
            'ngnPerUsd' => Setting::ngnPerUsd(),
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
            'ngnPerUsd' => Setting::ngnPerUsd(),
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
     * Validate the form and convert it to product attributes (naira price plus its dollar price in cents, image as a public path).
     *
     * @return array{category_id: int, name: string, summary: ?string, description: ?string, price_naira: int, price_cents: int, in_stock: bool, image?: string}
     */
    private function validated(Request $request, bool $imageRequired): array
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'price' => ['required', 'integer', 'min:1000', 'max:100000000'],
            'summary' => ['nullable', 'string', 'max:5000'],
            'description' => ['nullable', 'string', 'max:20000'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ]);

        $data = [
            'category_id' => (int) $input['category_id'],
            'name' => $input['name'],
            'summary' => $input['summary'] ?? null,
            'description' => $input['description'] ?? null,
            'price_naira' => (int) $input['price'],
            'price_cents' => Product::usdCentsFor((int) $input['price']),
            'in_stock' => $request->boolean('in_stock'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = 'storage/'.$this->storeImage($request->file('image'));
        }

        return $data;
    }

    /**
     * Save an upload as a WebP no larger than 1600px so phone photos don't slow the shop down.
     * Falls back to the original file when the server's GD build can't decode or write WebP.
     */
    private function storeImage(UploadedFile $file): string
    {
        $image = function_exists('imagewebp') ? @imagecreatefromstring((string) file_get_contents($file->getRealPath())) : false;

        if (! $image) {
            return $file->store('products', 'public');
        }

        $orientation = function_exists('exif_read_data') && in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)
            ? (@exif_read_data($file->getRealPath())['Orientation'] ?? 1)
            : 1;
        $image = match ($orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };

        $longestSide = max(imagesx($image), imagesy($image));
        if ($longestSide > 1600) {
            $image = imagescale($image, (int) round(imagesx($image) * 1600 / $longestSide), (int) round(imagesy($image) * 1600 / $longestSide));
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        ob_start();
        imagewebp($image, null, 82);
        $path = 'products/'.Str::random(40).'.webp';
        Storage::disk('public')->put($path, (string) ob_get_clean());

        return $path;
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
