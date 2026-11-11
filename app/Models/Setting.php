<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Store-wide values the admin can change, keyed by name.
 */
#[Fillable(['key', 'value'])]
class Setting extends Model
{
    protected $primaryKey = 'key';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * Naira per US dollar, used to work out the dollar price of every product.
     */
    public static function ngnPerUsd(): int
    {
        return (int) (static::find('ngn_per_usd')?->value ?? config('store.ngn_per_usd'));
    }

    public static function setNgnPerUsd(int $rate): void
    {
        static::updateOrCreate(['key' => 'ngn_per_usd'], ['value' => (string) $rate]);
    }

    /**
     * Percentage added on top of the converted dollar price.
     */
    public static function usdMarkupPercent(): int
    {
        return (int) (static::find('usd_markup_percent')?->value ?? config('store.usd_markup_percent'));
    }

    public static function setUsdMarkupPercent(int $percent): void
    {
        static::updateOrCreate(['key' => 'usd_markup_percent'], ['value' => (string) $percent]);
    }
}
