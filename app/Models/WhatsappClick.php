<?php

namespace App\Models;

use Database\Factories\WhatsappClickFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A visitor opening WhatsApp from the shop. The contact is copied onto the row so the
 * history survives the admin renaming or replacing numbers.
 */
#[Fillable(['contact_label', 'contact_number', 'source', 'visitor', 'user_id', 'product_id', 'cart_items', 'cart_total_cents'])]
class WhatsappClick extends Model
{
    /** @use HasFactory<WhatsappClickFactory> */
    use HasFactory;

    public const UPDATED_AT = null;

    /**
     * Where the visitor tapped WhatsApp, keyed by the stored value.
     *
     * @var array<string, string>
     */
    public const SOURCES = [
        'chat' => 'Chat',
        'order' => 'Cart order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sourceLabel(): string
    {
        return self::SOURCES[$this->source] ?? ucfirst($this->source);
    }
}
