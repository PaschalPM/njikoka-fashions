<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Hausa babban riga menswear, keyed by slug; the photo shares the slug's name.
     *
     * @var array<string, array{name: string, summary: string, description: string, price_naira: int}>
     */
    private const PRODUCTS = [
        'sky-blue-babban-riga' => [
            'name' => 'Sky Blue Babban Riga with Geometric Embroidery',
            'summary' => 'A sky blue Hausa babban riga with a bold geometric embroidered panel, worn over a matching inner kaftan and trousers.',
            'description' => '<p>A sharp sky blue babban riga in a lightly textured fabric, with a large geometric embroidery panel in deep blue running from the collar down the chest, layered over a matching inner kaftan and trousers.</p><ul><li>Wide, flowing outer robe with generous sleeves</li><li>Deep blue geometric embroidered chest panel</li><li>Matching long inner kaftan and trousers</li><li>Shown with a dark embroidered cap</li></ul><p>A standout look for grooms, wedding guests, Sallah and formal events.</p>',
            'price_naira' => 195000,
        ],
        'ivory-babban-riga' => [
            'name' => 'Ivory Babban Riga with Embroidered Front',
            'summary' => 'An ivory Hausa babban riga with brown and white geometric embroidery at the chest and cuffs.',
            'description' => '<p>A clean ivory babban riga with a V-shaped panel of brown and white geometric embroidery at the chest, embroidered cuffs and a wide, flowing outer robe, over a matching inner kaftan and trousers.</p><ul><li>Wide, open-sided outer robe</li><li>Geometric embroidered V-panel at the chest</li><li>Embroidered cuffs</li><li>Matching long inner kaftan and trousers</li><li>Shown with a striped embroidered cap and a tan leather clutch</li></ul><p>Polished for weddings, naming ceremonies, Friday prayers and Sallah.</p>',
            'price_naira' => 185000,
        ],
    ];

    /**
     * Add each product unless one with that slug already exists.
     */
    public function up(): void
    {
        $menId = DB::table('categories')->where('slug', 'men')->value('id');

        if (! $menId) {
            return;
        }

        $setting = fn (string $key) => DB::table('settings')->where('key', $key)->value('value');
        $ngnPerUsd = (int) ($setting('ngn_per_usd') ?? config('store.ngn_per_usd'));
        $markupPercent = (int) ($setting('usd_markup_percent') ?? config('store.usd_markup_percent'));

        foreach (self::PRODUCTS as $slug => $product) {
            if (DB::table('products')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('products')->insert([
                ...$product,
                'slug' => $slug,
                'category_id' => $menId,
                'image' => "images/products/{$slug}.webp",
                'price_cents' => max(1, (int) round($product['price_naira'] / $ngnPerUsd * (100 + $markupPercent) / 100)) * 100,
                'in_stock' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
