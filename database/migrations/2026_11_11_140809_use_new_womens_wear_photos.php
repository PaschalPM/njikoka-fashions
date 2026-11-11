<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Old sample photo => replacement women's wear photo. Products whose photo was changed in the admin are left alone.
     *
     * @var array<string, string>
     */
    private const PHOTOS = [
        'images/products/Patrick-U-Patrick_R1-08-scaled.webp' => 'images/products/nigerian-fashion-01.webp',
        'images/products/Patrick-U-Patrick_R1-09-scaled.webp' => 'images/products/nigerian-fashion-03.webp',
        'images/products/Patrick-U-Patrick_R1-04-scaled.webp' => 'images/products/nigerian-fashion-05.webp',
        'images/products/Patrick-U-Patrick_R1-05-scaled.webp' => 'images/products/nigerian-fashion-17.webp',
        'images/products/Patrick-U-Patrick_R1-13-scaled.webp' => 'images/products/nigerian-fashion-06.webp',
        'images/products/Patrick-U-Patrick_R1-07-scaled.webp' => 'images/products/nigerian-fashion-18.webp',
        'images/products/Patrick-U-Patrick_R1-01-scaled.webp' => 'images/products/nigerian-fashion-07.webp',
        'images/products/Patrick-U-Patrick_R1-02-scaled.webp' => 'images/products/nigerian-fashion-02.webp',
        'images/products/Patrick-U-Patrick_R1-03-scaled.webp' => 'images/products/nigerian-fashion-12.webp',
        'images/products/Patrick-U-Patrick_R1-14-scaled.webp' => 'images/products/nigerian-fashion-13.webp',
        'images/products/Patrick-U-Patrick_R1-15-scaled.webp' => 'images/products/nigerian-fashion-04.webp',
        'images/products/Patrick-U-Patrick_R1-10-scaled.webp' => 'images/products/nigerian-fashion-19.webp',
        'images/products/Patrick-U-Patrick_R1-11-scaled.webp' => 'images/products/nigerian-fashion-08.webp',
        'images/products/Patrick-U-Patrick_R1-12-scaled.webp' => 'images/products/nigerian-fashion-20.webp',
        'images/products/Patrick-U-Patrick_R1-06-scaled.webp' => 'images/products/nigerian-fashion-10.webp',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::PHOTOS as $old => $new) {
            DB::table('products')->where('image', $old)->update(['image' => $new]);
        }
    }
};
