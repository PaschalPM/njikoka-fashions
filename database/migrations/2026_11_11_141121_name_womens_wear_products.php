<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Slug => [placeholder name, new name, summary, description] for the women's wear sample products.
     *
     * @var array<string, array{0: string, 1: string, 2: string, 3: string}>
     */
    private const PRODUCTS = [
        'dress-1' => ['Dress 1', 'Rose Print Wrapper Dress', 'A fitted red dress covered in soft pink roses, finished with a matching head tie.', '<p>Deep red with an all-over rose print, this dress has a fitted bodice and short ruffled cap sleeves that frame the shoulders. The matching head tie completes the look.</p><p>Wear it to weddings, naming ceremonies or Sunday service, and add statement gold earrings to finish.</p>'],
        'dress-2' => ['Dress 2', 'Plum Lace Ball Gown', 'A floor-length plum gown with sheer long sleeves and a full, sweeping skirt.', '<p>A deep plum ball gown with a fitted lace bodice, sheer long sleeves and a dramatic full skirt that pools at the floor. Pair it with a matching gele for a complete owambe look.</p><p>Made for receptions, engagements and evening celebrations.</p>'],
        'dress-3' => ['Dress 3', 'Leopard Print Bubu Kaftan', 'A relaxed, flowing bubu in a bold leopard print, shown in coral and navy.', '<p>This roomy bubu kaftan drapes loosely from the shoulders for easy, all-day comfort. The bold leopard print comes in coral and navy, each with a matching head wrap.</p><p>Dress it up with gold jewellery or keep it simple for everyday wear. Message us to choose your colour.</p>'],
        'dress-4' => ['Dress 4', 'Midnight Stripe Head Wrap', 'A navy head wrap with fine white stripes, tied high for a sculpted crown.', '<p>Deep navy with fine white stripes, this head wrap ties into a tall, sculpted crown. It is shown with a red top and beaded bracelets.</p><p>Wear it with traditional or modern outfits for an instant statement.</p>'],
        'dress-5' => ['Dress 10', 'Garden Floral Ruffle Maxi', 'A floor-length floral maxi dress with ruffled sleeves, styled with a teal head wrap.', '<p>A soft floral print on a neutral ground, with ruffled sleeves and a fitted waist that falls into a full-length skirt. The teal head wrap adds a pop of colour.</p><p>A graceful choice for garden parties, church and daytime events.</p>'],
        'dress-6' => ['Dress 6', 'Sunburst Ankara Head Wrap', 'A bright yellow and orange Ankara head wrap tied into a full, rounded shape.', '<p>Bold yellow, orange and red Ankara print, wrapped high and full around the head. A bright, joyful accent for any outfit.</p><p>Style it with a matching Ankara dress or let it stand out against a plain top.</p>'],
        'dress-7' => ['Dress 7', 'Golden Kente Crop Set', 'A kente-pattern crop top with full black sleeves, styled with a braided gold crown.', '<p>A kente-pattern crop top in orange, green and gold with full, voluminous black sleeves. It is shown with a braided gold head crown and layered gold jewellery.</p><p>Made for cultural festivals, photoshoots and celebrations where you want to be seen.</p>'],
        'dress-8' => ['Dress 8', 'Indigo Stripe Wrap Set', 'A blue and gold striped wrap with a matching head tie.', '<p>Rich indigo with gold and black stripes, this wrap drapes over the shoulders and comes with a matching head tie that ties into a full crown.</p><p>Elegant for traditional ceremonies and family gatherings.</p>'],
        'dress-9' => ['Dress 9', 'Amber Leaf Ankara Gown', 'A long-sleeved Ankara gown in an amber leaf print with an embellished neckline.', '<p>Warm amber and brown Ankara with a leaf print, cut as a modest long-sleeved gown with an embellished yoke at the neckline. It is shown with a matching head tie and a grey hijab.</p><p>A polished option for Jumat, Eid celebrations and formal visits.</p>'],
        'dress-11' => ['Dress 11', 'Festival Print Head Wrap', 'A vivid multicolour Ankara head wrap, shown in three colourways.', '<p>Big, bold head wraps in bright Ankara prints of blue, yellow, pink and green. They are shown styled with denim for a modern, festival-ready look.</p><p>Message us to choose your print.</p>'],
        'dress-12' => ['Dress 12', 'Lime Ankara Wrapper Set', 'A lime and black Ankara wrapper set with a matching head tie and a blue shawl.', '<p>A lively lime, black and blue Ankara print, worn as a loose top and wrapper with a matching head tie. A royal blue shawl drapes over one shoulder.</p><p>Comfortable and colourful for daytime events and family visits.</p>'],
        'dress-13' => ['Dress 13', 'Indigo and Coral Head Wrap Set', 'An indigo striped head wrap paired with a coral top and beaded bracelets.', '<p>An indigo head wrap with white stripes, styled with a coral top, beaded bracelets in red, green and white, and statement earrings.</p><p>A ready-to-wear look for festivals and cultural days.</p>'],
        'dress-14' => ['Dress 14', 'Onyx Draped Turban', 'A soft black turban draped high, styled with gold geometric earrings.', '<p>A minimal black turban with soft draping that sits high at the crown. It is shown with a black top and gold geometric earrings.</p><p>Easy to wear with anything, from office looks to evening outfits.</p>'],
        'dress-15' => ['Dress 15', 'Marigold Print Gele', 'A marigold and orange Ankara gele tied into a tall, structured crown.', '<p>Marigold yellow and orange Ankara print, tied into a tall, structured gele. Bright enough to lead any outfit.</p><p>Pair it with gold jewellery and a bold lip for weddings and celebrations.</p>'],
        'dress-5-2' => ['Dress 5', 'Indigo Stripe Head Tie', 'A blue and gold striped head tie, wrapped full and high.', '<p>Indigo with gold and black stripes, tied into a full, high crown. It matches our Indigo Stripe Wrap Set.</p><p>Wear it alone or with the wrap for a complete look.</p>'],
    ];

    /**
     * Replace the placeholder names and lorem ipsum copy. Products already renamed in the admin are left alone.
     */
    public function up(): void
    {
        foreach (self::PRODUCTS as $slug => [$placeholder, $name, $summary, $description]) {
            DB::table('products')
                ->where('slug', $slug)
                ->where('name', $placeholder)
                ->update(['name' => $name, 'summary' => $summary, 'description' => $description]);
        }
    }
};
