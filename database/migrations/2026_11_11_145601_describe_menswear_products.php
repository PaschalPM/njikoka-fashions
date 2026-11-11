<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Names and details for the menswear sample products, keyed by slug.
     *
     * @var array<string, array{name: string, summary: string, description: string, price_naira: int}>
     */
    private const PRODUCTS = [
        'dress' => [
            'name' => 'Tan Embroidered Short-Sleeve Senator',
            'summary' => 'A camel-tan senator top and trousers with tonal embroidery on the chest.',
            'description' => '<p>A clean two-piece senator in warm camel tan, with short sleeves and a relaxed, straight cut that sits just below the hip.</p><ul><li>Round neck with a short concealed placket</li><li>Tonal embroidered motifs on both sides of the chest</li><li>Short sleeves and a straight hem</li><li>Matching slim-leg trousers</li></ul><p>Pair it with brown loafers and a watch for owambe parties, Friday wear and family events.</p>',
            'price_naira' => 150000,
        ],
        'dress-10' => [
            'name' => 'Olive Long Kaftan with Embroidered Placket',
            'summary' => 'A knee-length olive kaftan with an embroidered placket and pocket, worn over matching trousers.',
            'description' => '<p>A knee-length kaftan in muted olive green with a band collar, three-quarter sleeves and an easy, flowing cut.</p><ul><li>Band collar with an embroidered button placket</li><li>Embroidered chest pocket</li><li>Three-quarter sleeves and side slits</li><li>Matching straight-leg trousers</li></ul><p>Shown with a burgundy fila and brown loafers. A polished choice for weddings, church and Jumat.</p>',
            'price_naira' => 170000,
        ],
        'dress-16' => [
            'name' => 'White Short-Sleeve Senator with Chest Embroidery',
            'summary' => 'A crisp white senator with silver-grey embroidery on the chest and matching white trousers.',
            'description' => '<p>An all-white short-sleeve senator with fine silver-grey embroidery across both sides of the chest.</p><ul><li>Band collar with a short button placket</li><li>Embroidered chest panels and a chest pocket</li><li>Short sleeves and a straight hem</li><li>Matching white trousers</li></ul><p>Fresh and sharp for thanksgiving services, white-themed parties and summer events.</p>',
            'price_naira' => 150000,
        ],
        'dress-17' => [
            'name' => 'Grey Long-Sleeve Senator with Black Embroidery',
            'summary' => 'A grey long-sleeve senator with black embroidery and buttons down the front.',
            'description' => '<p>A mid-grey senator top cut to the upper thigh, with long sleeves and contrasting black detail down the front.</p><ul><li>Round collar</li><li>Black embroidered motifs and buttons along the placket</li><li>Long sleeves and side slits</li><li>Matching tapered trousers</li></ul><p>Understated enough for the office and smart enough for meetings and evening events.</p>',
            'price_naira' => 165000,
        ],
        'dress-18' => [
            'name' => 'Beige Button-Front Senator Set',
            'summary' => 'A beige long-sleeve senator with an offset dark-button placket and matching trousers.',
            'description' => '<p>A soft beige senator top with long sleeves and an offset button placket that gives a modern, clean line.</p><ul><li>Mandarin collar</li><li>Offset placket with dark contrast buttons</li><li>Long sleeves and a thigh-length hem</li><li>Matching straight trousers</li></ul><p>An easy everyday outfit that works for work, church and casual Fridays.</p>',
            'price_naira' => 140000,
        ],
        'dress-19' => [
            'name' => 'White Long Kaftan with Patterned Pocket',
            'summary' => 'A long white kaftan with a black and white patterned chest pocket, worn over white trousers.',
            'description' => '<p>A knee-length white kaftan with long sleeves and a single patterned chest pocket for contrast.</p><ul><li>Band collar with a concealed placket</li><li>Black and white patterned chest pocket</li><li>Long sleeves and side slits</li><li>Matching white trousers</li></ul><p>Shown with an embroidered cap. An elegant choice for weddings, thanksgiving services and Eid.</p>',
            'price_naira' => 175000,
        ],
        'dress-20' => [
            'name' => 'White Senator with Navy Embroidered Placket',
            'summary' => 'A white long-sleeve senator with navy embroidery down the placket and a framed chest pocket.',
            'description' => '<p>A white senator top with long sleeves, finished with a column of navy embroidery down the placket and a navy-framed chest pocket.</p><ul><li>Round collar</li><li>Navy embroidered placket</li><li>Navy-framed chest pocket</li><li>Long sleeves and a thigh-length hem</li><li>Matching white trousers</li></ul><p>A standout look for weddings, birthdays and special occasions.</p>',
            'price_naira' => 160000,
        ],
        'dress-21' => [
            'name' => 'Sky Blue Senator with Navy Piping',
            'summary' => 'A short-sleeved sky blue senator with navy piping along the collar, placket and pocket.',
            'description' => '<p>A bright sky blue senator top with short sleeves and fine navy piping that traces the collar, the curved placket and the chest pocket.</p><ul><li>Round collar with navy piping</li><li>Curved, piped placket and pocket</li><li>Short sleeves and a thigh-length hem</li><li>Matching straight trousers</li></ul><p>Shown with a patterned cap. A cheerful choice for daytime events, outings and Sunday wear.</p>',
            'price_naira' => 145000,
        ],
    ];

    /**
     * Only products still named "Dress" are updated, so anything renamed in the admin is kept.
     */
    public function up(): void
    {
        $ngnPerUsd = (int) (DB::table('settings')->where('key', 'ngn_per_usd')->value('value') ?? config('store.ngn_per_usd'));

        foreach (self::PRODUCTS as $slug => $product) {
            DB::table('products')
                ->where('slug', $slug)
                ->where('name', 'Dress')
                ->update([...$product, 'price_cents' => max(1, (int) round($product['price_naira'] / $ngnPerUsd)) * 100]);
        }
    }
};
