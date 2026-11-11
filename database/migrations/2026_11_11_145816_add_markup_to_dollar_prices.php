<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Reprice every product's dollar amount with the dollar markup (20% unless configured otherwise).
     */
    public function up(): void
    {
        $setting = fn (string $key) => DB::table('settings')->where('key', $key)->value('value');
        $ngnPerUsd = (int) ($setting('ngn_per_usd') ?? config('store.ngn_per_usd'));
        $markupPercent = (int) ($setting('usd_markup_percent') ?? config('store.usd_markup_percent'));

        DB::table('products')->get(['id', 'price_naira'])->each(fn (object $product) => DB::table('products')
            ->where('id', $product->id)
            ->update(['price_cents' => max(1, (int) round($product->price_naira / $ngnPerUsd * (100 + $markupPercent) / 100)) * 100]));
    }
};
