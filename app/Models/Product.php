<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'slug', 'summary', 'description', 'price_naira', 'price_cents', 'image', 'image_credit', 'image_credit_url', 'in_stock'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'in_stock' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Price in naira, the store's main currency.
     */
    public function formattedPrice(): string
    {
        return static::formatNaira($this->price_naira);
    }

    /**
     * Dollar price charged at card checkout.
     */
    public function formattedUsdPrice(): string
    {
        return static::formatUsd($this->price_cents);
    }

    /**
     * Whole-dollar price in cents for a naira amount: converted at the admin's exchange rate, then marked up by the admin's percentage.
     */
    public static function usdCentsFor(int $naira, ?int $ngnPerUsd = null, ?int $markupPercent = null): int
    {
        $dollars = $naira / ($ngnPerUsd ?? Setting::ngnPerUsd()) * (100 + ($markupPercent ?? Setting::usdMarkupPercent())) / 100;

        return max(1, (int) round($dollars)) * 100;
    }

    public static function formatNaira(int $naira): string
    {
        return '₦'.number_format($naira);
    }

    public static function formatUsd(int $cents): string
    {
        return '$'.number_format($cents / 100, $cents % 100 === 0 ? 0 : 2);
    }
}
