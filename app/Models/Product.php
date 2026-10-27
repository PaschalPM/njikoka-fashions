<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'slug', 'summary', 'description', 'price_cents', 'image', 'in_stock'])]
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

    public function formattedPrice(): string
    {
        return '$'.number_format($this->price_cents / 100, 2);
    }
}
