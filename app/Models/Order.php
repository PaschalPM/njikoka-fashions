<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'user_id', 'number', 'status', 'first_name', 'last_name', 'email', 'phone',
    'address_line1', 'address_line2', 'city', 'state', 'postcode', 'country', 'notes',
    'total_cents', 'stripe_session_id', 'stripe_payment_intent', 'paid_at',
])]
class Order extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->number ??= 'NJK-'.strtoupper(Str::random(8));
        });
    }

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName(): string
    {
        return 'number';
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    /**
     * Mark the order as paid. Safe to call more than once (success redirect and webhook).
     */
    public function markPaid(?string $paymentIntent = null): void
    {
        if ($this->isPaid()) {
            return;
        }

        $this->update([
            'status' => 'processing',
            'paid_at' => now(),
            'stripe_payment_intent' => $paymentIntent,
        ]);
    }

    public function formattedTotal(): string
    {
        return '$'.number_format($this->total_cents / 100, 2);
    }
}
