<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Women's products moving to full-length Igbo and Yoruba photos, keyed by slug.
     *
     * @var array<string, array<string, string|int>>
     */
    private const PRODUCTS = [
        'dress-1' => [
            'old_image' => 'images/products/nigerian-fashion-01.webp',
            'image' => 'images/products/igbo-red-george-double-wrapper.webp',
            'name' => 'Red George Double Wrapper Set',
            'summary' => 'An Igbo double wrapper in rich red George with a gold border, worn with a white puff-sleeve blouse and a matching red ichafu.',
            'description' => '<p>A classic Igbo look: two wrappers in red George, the top one finished with a gold border. It is worn with a crisp white uwe obi blouse with puffed sleeves and a sculpted red ichafu head tie.</p><p>Made for traditional weddings, igba nkwu and family celebrations. A matching red clutch completes the outfit.</p>',
            'price_naira' => 190000,
        ],
        'dress-6' => [
            'old_image' => 'images/products/nigerian-fashion-18.webp',
            'image' => 'images/products/igbo-akwete-double-wrapper.webp',
            'name' => 'Akwete Double Wrapper Set',
            'summary' => 'An Igbo obiakwa double wrapper in handwoven-pattern Akwete, with a cream ruffled blouse and a pink ichafu.',
            'description' => '<p>Two Akwete wrappers in a bold pink, black and white woven pattern, worn as the traditional obiakwa double wrapper. It is paired with a cream blouse with sculpted ruffle sleeves and a soft pink ichafu.</p><p>An elegant choice for traditional weddings, church thanksgiving and cultural days.</p>',
            'price_naira' => 170000,
        ],
        'dress-7' => [
            'old_image' => 'images/products/nigerian-fashion-07.webp',
            'image' => 'images/products/yoruba-peach-lace-buba-iro.webp',
            'name' => 'Peach Lace Buba and Iro',
            'summary' => 'A Yoruba buba and iro in soft peach embroidered lace with wide, flowing sleeves.',
            'description' => '<p>A loose peach buba with wide, flowing sleeves and an embroidered finish, worn over a matching iro wrapper. Light and graceful, with room to move.</p><p>Wear it to engagements, owambe parties and naming ceremonies, with a gele or with your hair styled up.</p>',
            'price_naira' => 180000,
        ],
        'dress-8' => [
            'old_image' => 'images/products/nigerian-fashion-02.webp',
            'image' => 'images/products/igbo-akwete-wrap-skirt.webp',
            'name' => 'Akwete Wrap Skirt Set',
            'summary' => 'A multicolour Akwete wrap skirt with a draped panel, worn with a sheer-sleeved black top and an olive ichafu.',
            'description' => '<p>A fitted wrap skirt in colourful Akwete-pattern fabric, with a draped panel falling at the hip. It is worn with a black top with sheer long sleeves and an olive ichafu head tie.</p><p>A modern take on Igbo dressing, polished enough for events and easy to wear all day.</p>',
            'price_naira' => 150000,
        ],
        'dress-9' => [
            'old_image' => 'images/products/nigerian-fashion-12.webp',
            'image' => 'images/products/coral-bead-print-wrapper.webp',
            'name' => 'Coral Bead Print Wrapper Set',
            'summary' => 'A printed wrapper tied at the chest and waist, with a matching gele and layered coral beads.',
            'description' => '<p>A cream, brown and red print wrapper, tied at the chest with a second wrapper at the waist, and a matching gele. It is shown with layered red coral beads and a beaded armlet.</p><p>A striking look for cultural festivals, photoshoots and traditional ceremonies.</p>',
            'price_naira' => 120000,
        ],
        'dress-11' => [
            'old_image' => 'images/products/nigerian-fashion-13.webp',
            'image' => 'images/products/igbo-isiagu-maiden-dress.webp',
            'name' => 'Isiagu Maiden Dress',
            'summary' => 'A fitted Igbo maiden dress in red and gold Isiagu print, styled with coral beads.',
            'description' => '<p>A fitted short dress with an off-shoulder neckline, in the red and gold lion-head Isiagu print traditionally worn by Igbo royalty. It is styled with coral beads at the neck and ankles and a horsetail fan.</p><p>Made for igba nkwu, cultural days and celebrations where you want to honour tradition.</p>',
            'price_naira' => 150000,
        ],
        'dress-13' => [
            'old_image' => 'images/products/nigerian-fashion-19.webp',
            'image' => 'images/products/igbo-star-print-flare-dress.webp',
            'name' => 'Star Print Flare Dress',
            'summary' => 'An orange star-print dress with a full flared skirt and ruffled off-shoulder sleeves, worn with an orange ichafu.',
            'description' => '<p>A bright orange dress covered in a red and black star print, with ruffled off-shoulder sleeves, a halter neck and a full, swinging skirt. It is worn with a matching orange ichafu.</p><p>Cheerful and easy to wear to parties, outings and Sunday lunches.</p>',
            'price_naira' => 130000,
        ],
        'dress-14' => [
            'old_image' => 'images/products/nigerian-fashion-08.webp',
            'image' => 'images/products/igbo-emerald-sequin-wrapper-gown.webp',
            'name' => 'Emerald Sequin Wrapper Gown',
            'summary' => 'A floor-length emerald gown in sequinned fabric with an off-shoulder ruffle neckline and a matching ichafu.',
            'description' => '<p>Deep emerald sequinned fabric shaped into a fitted, floor-length gown with nested wrapper layers and a dramatic off-shoulder ruffle. It is finished with a matching emerald ichafu, red coral beads and a beaded hand fan.</p><p>A showpiece for traditional weddings and evening receptions.</p>',
            'price_naira' => 200000,
        ],
        'dress-15' => [
            'old_image' => 'images/products/nigerian-fashion-20.webp',
            'image' => 'images/products/igbo-etireti-madras-wrapper.webp',
            'name' => 'Etireti Madras Wrapper Set',
            'summary' => 'A short etireti-style madras wrapper with a black off-shoulder top and coral beads.',
            'description' => '<p>A short wrapper in pink and green madras check, tied in the etireti style, with a structured black off-shoulder top. It is styled with coral beads at the neck and ankles and a horsetail whisk.</p><p>A youthful traditional look for cultural days, dance performances and celebrations.</p>',
            'price_naira' => 140000,
        ],
        'dress-5-2' => [
            'old_image' => 'images/products/nigerian-fashion-10.webp',
            'image' => 'images/products/igbo-short-madras-wrapper-coral.webp',
            'name' => 'Short Madras Wrapper Set with Coral Beads',
            'summary' => 'A red and black madras wrapper set with an off-shoulder top, finished with bold coral beads and a coral headpiece.',
            'description' => '<p>A red and black madras check wrapper and matching off-shoulder top, cinched at the waist with a beaded belt. It is styled with a full coral bead necklace and a coral headpiece.</p><p>Made for igba nkwu, maiden ceremonies and cultural festivals.</p>',
            'price_naira' => 160000,
        ],
    ];

    /**
     * Only products still showing the previous sample photo are updated, so edits made in the admin are kept.
     */
    public function up(): void
    {
        $ngnPerUsd = (int) (DB::table('settings')->where('key', 'ngn_per_usd')->value('value') ?? config('store.ngn_per_usd'));

        foreach (self::PRODUCTS as $slug => $product) {
            ['old_image' => $oldImage, 'price_naira' => $priceNaira] = $product;
            unset($product['old_image']);

            DB::table('products')
                ->where('slug', $slug)
                ->where('image', $oldImage)
                ->update([
                    ...$product,
                    'image_credit' => $product['image_credit'] ?? null,
                    'image_credit_url' => $product['image_credit_url'] ?? null,
                    'price_cents' => max(1, (int) round($priceNaira / $ngnPerUsd)) * 100,
                ]);
        }
    }
};
