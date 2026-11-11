<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Naira prices for the catalogue, covering materials, workmanship and delivery.
     *
     * @var array<string, int>
     */
    private const PRICES = [
        'dress-2' => 200000,
        'dress-7' => 180000,
        'dress-5' => 170000,
        'dress-9' => 160000,
        'dress-1' => 150000,
        'dress-8' => 150000,
        'dress-12' => 140000,
        'dress-3' => 130000,
        'dress-13' => 120000,
        'dress-11' => 110000,
        'dress-15' => 110000,
        'dress-4' => 100000,
        'dress-6' => 100000,
        'dress-14' => 100000,
        'dress-5-2' => 100000,
        'dress' => 150000,
        'dress-10' => 170000,
        'dress-16' => 150000,
        'dress-17' => 165000,
        'dress-18' => 140000,
        'dress-19' => 175000,
        'dress-20' => 160000,
        'dress-21' => 145000,
    ];

    /**
     * Prices become naira-first; the dollar price is worked out from the exchange rate.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('price_naira')->default(0)->after('description');
        });

        $rate = (int) config('store.ngn_per_usd');

        DB::table('products')->get(['id', 'slug', 'price_cents'])->each(function (object $product) use ($rate) {
            $naira = self::PRICES[$product->slug] ?? (int) round($product->price_cents / 100 * $rate, -3);

            DB::table('products')->where('id', $product->id)->update([
                'price_naira' => $naira,
                'price_cents' => max(1, (int) round($naira / $rate)) * 100,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price_naira');
        });
    }
};
