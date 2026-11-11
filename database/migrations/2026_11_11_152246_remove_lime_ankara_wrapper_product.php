<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Take the women's "dress-12" sample product off the shop. Order items keep their own name and price
     * (product_id is nulled), and its reviews are removed with it.
     */
    public function up(): void
    {
        DB::table('products')->where('slug', 'dress-12')->delete();
    }
};
