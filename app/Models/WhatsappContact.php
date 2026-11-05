<?php

namespace App\Models;

use Database\Factories\WhatsappContactFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

#[Fillable(['label', 'number', 'position'])]
class WhatsappContact extends Model
{
    /** @use HasFactory<WhatsappContactFactory> */
    use HasFactory;

    /**
     * How many numbers the admin can list for customers to reach.
     */
    public const SLOTS = 3;

    /**
     * Contacts shown in the "Chat with us" button and the cart, in admin order.
     *
     * @return Collection<int, WhatsappContact>
     */
    public static function forStorefront(): Collection
    {
        return static::orderBy('position')->get();
    }

    public function chatUrl(string $message): string
    {
        return 'https://wa.me/'.$this->number.'?text='.rawurlencode($message);
    }

    public function displayNumber(): string
    {
        return '+'.$this->number;
    }
}
