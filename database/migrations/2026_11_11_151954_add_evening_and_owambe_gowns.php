<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * New women's gowns (owambe and international evening wear), keyed by slug; the photo shares the slug's name.
     *
     * @var array<string, array{name: string, summary: string, description: string, price_naira: int}>
     */
    private const PRODUCTS = [
        'azure-draped-gown-with-gele' => [
            'name' => 'Azure Draped Gown with Turban Gele',
            'summary' => 'A floor-length azure gown with a draped, gathered skirt and a beaded purple bodice, styled with a pink turban gele.',
            'description' => '<p>A soft azure gown with a fitted bodice in purple and blue beadwork, short sleeves and a skirt draped and gathered at the front so it falls in rich folds to the floor.</p><ul><li>Sweetheart neckline with beaded bodice</li><li>Short cap sleeves</li><li>Draped, gathered front and a floor-length hem</li><li>Shown with a pink turban gele and statement jewellery</li></ul><p>An owambe-ready look for weddings, engagements and birthday celebrations.</p>',
            'price_naira' => 170000,
        ],
        'emerald-gold-puff-sleeve-gown' => [
            'name' => 'Emerald and Gold Puff-Sleeve Gown',
            'summary' => 'A full-skirted emerald gown with ruffled puff sleeves and bold gold embellishment from neckline to hem.',
            'description' => '<p>A rich emerald gown with a structured bodice, sculpted puff sleeves and a full skirt, decorated with bold gold motifs and finished with a wide gold border at the hem.</p><ul><li>Scoop neckline with gold trim</li><li>Ruffled puff sleeves</li><li>Peplum waist detail</li><li>Full, floor-length skirt with a gold border</li></ul><p>Made for traditional weddings, receptions and milestone celebrations.</p>',
            'price_naira' => 190000,
        ],
        'silver-lace-mermaid-gown-with-gele' => [
            'name' => 'Silver Lace Mermaid Gown with Gele',
            'summary' => 'A fitted mermaid gown in silver and white floral lace with a dramatic draped sleeve and a matching gele.',
            'description' => '<p>A body-skimming mermaid gown covered in silver and white 3D floral lace, with a one-shoulder neckline and a dramatic draped lace sleeve. A matching lace gele completes the look.</p><ul><li>One-shoulder neckline</li><li>3D floral lace all over</li><li>Fitted mermaid silhouette to the floor</li><li>Matching gele and beaded purse</li></ul><p>A showstopper for weddings, aso ebi and evening receptions.</p>',
            'price_naira' => 190000,
        ],
        'cobalt-floral-applique-gown' => [
            'name' => 'Cobalt Floral Appliqué Gown',
            'summary' => 'A cobalt blue gown covered in 3D floral appliqué, with white flowers at the neckline and a fuchsia flower headpiece.',
            'description' => '<p>A fitted cobalt blue gown worked all over with textured 3D floral appliqué, with white blooms framing an off-the-shoulder neckline and a flared hem.</p><ul><li>Off-the-shoulder neckline with white floral trim</li><li>Sheer short sleeves</li><li>Textured floral appliqué from bodice to hem</li><li>Shown with a fuchsia flower headpiece</li></ul><p>A bold choice for owambe parties, birthdays and themed celebrations.</p>',
            'price_naira' => 185000,
        ],
        'leopard-print-mermaid-gown' => [
            'name' => 'Leopard Print Mermaid Gown',
            'summary' => 'An off-the-shoulder leopard print mermaid gown with a sweetheart neckline and a sweeping train.',
            'description' => '<p>A sculpted mermaid gown in a glossy leopard print, with an off-the-shoulder sweetheart neckline, a fitted body and a train that pools around the feet.</p><ul><li>Off-the-shoulder sweetheart neckline</li><li>Fitted to the knee</li><li>Flared mermaid skirt with a train</li></ul><p>A glamorous statement for galas, birthdays and evening parties.</p>',
            'price_naira' => 175000,
        ],
        'ruby-sequin-slit-gown' => [
            'name' => 'Ruby Sequin Gown with Thigh Slit',
            'summary' => 'A ruby red sequin column gown with a sculpted floral shoulder, feather detail and a high thigh slit.',
            'description' => '<p>A fitted ruby red column gown covered in sequins, with a sculpted floral detail at the shoulder, feather accents and a high front slit.</p><ul><li>Strapless sweetheart neckline with a floral shoulder</li><li>All-over red sequins</li><li>Feather accents at the waist</li><li>High thigh slit and a floor-length hem</li></ul><p>Made for red-carpet nights, award events and evening receptions.</p>',
            'price_naira' => 180000,
        ],
        'burgundy-velvet-gown-with-cape' => [
            'name' => 'Burgundy Velvet Gown with Cape',
            'summary' => 'An off-the-shoulder burgundy velvet gown with a full skirt and a long, flowing velvet cape.',
            'description' => '<p>A deep burgundy velvet gown with an off-the-shoulder neckline, a fitted bodice and a full, softly gathered skirt, paired with a long, dark velvet cape.</p><ul><li>Off-the-shoulder neckline with puffed sleeves</li><li>Fitted, gathered bodice</li><li>Full floor-length skirt</li><li>Long velvet cape</li></ul><p>A regal look for evening galas, receptions and winter weddings.</p>',
            'price_naira' => 200000,
        ],
        'black-ivory-fringe-gown' => [
            'name' => 'Black and Ivory Fringe Gown',
            'summary' => 'A one-shoulder black and ivory column gown with a ruffled black shoulder, beaded detail and cascading fringe.',
            'description' => '<p>A striking two-tone gown in black and ivory, with a sculpted ruffled shoulder, gold beading on the bodice and ivory fringe cascading down one side to the floor.</p><ul><li>One-shoulder neckline with a ruffled black sleeve</li><li>Gold beaded bodice</li><li>Ivory fringe panel down the skirt</li><li>Front slit and floor-length hem</li></ul><p>A dramatic choice for galas, premieres and evening events.</p>',
            'price_naira' => 180000,
        ],
    ];

    /**
     * Add each gown unless a product with that slug already exists.
     */
    public function up(): void
    {
        $womenId = DB::table('categories')->where('slug', 'women')->value('id');

        if (! $womenId) {
            return;
        }

        $setting = fn (string $key) => DB::table('settings')->where('key', $key)->value('value');
        $ngnPerUsd = (int) ($setting('ngn_per_usd') ?? config('store.ngn_per_usd'));
        $markupPercent = (int) ($setting('usd_markup_percent') ?? config('store.usd_markup_percent'));

        foreach (self::PRODUCTS as $slug => $product) {
            if (DB::table('products')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::table('products')->insert([
                ...$product,
                'slug' => $slug,
                'category_id' => $womenId,
                'image' => "images/products/{$slug}.webp",
                'price_cents' => max(1, (int) round($product['price_naira'] / $ngnPerUsd * (100 + $markupPercent) / 100)) * 100,
                'in_stock' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
};
