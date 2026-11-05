<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The bundled sample photos were converted to WebP; repoint products seeded with the old PNG/JPG paths.
     * Photos uploaded through the admin live under storage/ and are left alone.
     */
    public function up(): void
    {
        DB::table('products')->where('image', 'like', 'images/products/%')->get(['id', 'image'])
            ->each(function (object $product) {
                $webp = preg_replace('/\.(png|jpe?g)$/i', '.webp', $product->image);

                if ($webp !== $product->image && file_exists(public_path($webp))) {
                    DB::table('products')->where('id', $product->id)->update(['image' => $webp]);
                }
            });
    }
};
