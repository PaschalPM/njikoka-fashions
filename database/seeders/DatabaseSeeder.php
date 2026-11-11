<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\WhatsappContact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the storefront with the catalogue from njikokafashions.com.
     */
    public function run(): void
    {
        $categories = collect(['Men', 'Women'])
            ->mapWithKeys(fn ($name) => [$name => Category::create(['name' => $name, 'slug' => Str::slug($name)])]);

        $products = [
            ['Rose Print Wrapper Dress', 'dress-1', 'Women', 150_000, 'images/products/nigerian-fashion-01.webp', 'A fitted red dress covered in soft pink roses, finished with a matching head tie.', '<p>Deep red with an all-over rose print, this dress has a fitted bodice and short ruffled cap sleeves that frame the shoulders. The matching head tie completes the look.</p><p>Wear it to weddings, naming ceremonies or Sunday service, and add statement gold earrings to finish.</p>'],
            ['Plum Lace Ball Gown', 'dress-2', 'Women', 200_000, 'images/products/nigerian-fashion-03.webp', 'A floor-length plum gown with sheer long sleeves and a full, sweeping skirt.', '<p>A deep plum ball gown with a fitted lace bodice, sheer long sleeves and a dramatic full skirt that pools at the floor. Pair it with a matching gele for a complete owambe look.</p><p>Made for receptions, engagements and evening celebrations.</p>'],
            ['Leopard Print Bubu Kaftan', 'dress-3', 'Women', 130_000, 'images/products/nigerian-fashion-05.webp', 'A relaxed, flowing bubu in a bold leopard print, shown in coral and navy.', '<p>This roomy bubu kaftan drapes loosely from the shoulders for easy, all-day comfort. The bold leopard print comes in coral and navy, each with a matching head wrap.</p><p>Dress it up with gold jewellery or keep it simple for everyday wear. Message us to choose your colour.</p>'],
            ['Midnight Stripe Head Wrap', 'dress-4', 'Women', 100_000, 'images/products/nigerian-fashion-17.webp', 'A navy head wrap with fine white stripes, tied high for a sculpted crown.', '<p>Deep navy with fine white stripes, this head wrap ties into a tall, sculpted crown. It is shown with a red top and beaded bracelets.</p><p>Wear it with traditional or modern outfits for an instant statement.</p>'],
            ['Garden Floral Ruffle Maxi', 'dress-5', 'Women', 170_000, 'images/products/nigerian-fashion-06.webp', 'A floor-length floral maxi dress with ruffled sleeves, styled with a teal head wrap.', '<p>A soft floral print on a neutral ground, with ruffled sleeves and a fitted waist that falls into a full-length skirt. The teal head wrap adds a pop of colour.</p><p>A graceful choice for garden parties, church and daytime events.</p>'],
            ['Sunburst Ankara Head Wrap', 'dress-6', 'Women', 100_000, 'images/products/nigerian-fashion-18.webp', 'A bright yellow and orange Ankara head wrap tied into a full, rounded shape.', '<p>Bold yellow, orange and red Ankara print, wrapped high and full around the head. A bright, joyful accent for any outfit.</p><p>Style it with a matching Ankara dress or let it stand out against a plain top.</p>'],
            ['Golden Kente Crop Set', 'dress-7', 'Women', 180_000, 'images/products/nigerian-fashion-07.webp', 'A kente-pattern crop top with full black sleeves, styled with a braided gold crown.', '<p>A kente-pattern crop top in orange, green and gold with full, voluminous black sleeves. It is shown with a braided gold head crown and layered gold jewellery.</p><p>Made for cultural festivals, photoshoots and celebrations where you want to be seen.</p>'],
            ['Indigo Stripe Wrap Set', 'dress-8', 'Women', 150_000, 'images/products/nigerian-fashion-02.webp', 'A blue and gold striped wrap with a matching head tie.', '<p>Rich indigo with gold and black stripes, this wrap drapes over the shoulders and comes with a matching head tie that ties into a full crown.</p><p>Elegant for traditional ceremonies and family gatherings.</p>'],
            ['Amber Leaf Ankara Gown', 'dress-9', 'Women', 160_000, 'images/products/nigerian-fashion-12.webp', 'A long-sleeved Ankara gown in an amber leaf print with an embellished neckline.', '<p>Warm amber and brown Ankara with a leaf print, cut as a modest long-sleeved gown with an embellished yoke at the neckline. It is shown with a matching head tie and a grey hijab.</p><p>A polished option for Jumat, Eid celebrations and formal visits.</p>'],
            ['Festival Print Head Wrap', 'dress-11', 'Women', 110_000, 'images/products/nigerian-fashion-13.webp', 'A vivid multicolour Ankara head wrap, shown in three colourways.', '<p>Big, bold head wraps in bright Ankara prints of blue, yellow, pink and green. They are shown styled with denim for a modern, festival-ready look.</p><p>Message us to choose your print.</p>'],
            ['Lime Ankara Wrapper Set', 'dress-12', 'Women', 140_000, 'images/products/nigerian-fashion-04.webp', 'A lime and black Ankara wrapper set with a matching head tie and a blue shawl.', '<p>A lively lime, black and blue Ankara print, worn as a loose top and wrapper with a matching head tie. A royal blue shawl drapes over one shoulder.</p><p>Comfortable and colourful for daytime events and family visits.</p>'],
            ['Indigo and Coral Head Wrap Set', 'dress-13', 'Women', 120_000, 'images/products/nigerian-fashion-19.webp', 'An indigo striped head wrap paired with a coral top and beaded bracelets.', '<p>An indigo head wrap with white stripes, styled with a coral top, beaded bracelets in red, green and white, and statement earrings.</p><p>A ready-to-wear look for festivals and cultural days.</p>'],
            ['Onyx Draped Turban', 'dress-14', 'Women', 100_000, 'images/products/nigerian-fashion-08.webp', 'A soft black turban draped high, styled with gold geometric earrings.', '<p>A minimal black turban with soft draping that sits high at the crown. It is shown with a black top and gold geometric earrings.</p><p>Easy to wear with anything, from office looks to evening outfits.</p>'],
            ['Marigold Print Gele', 'dress-15', 'Women', 110_000, 'images/products/nigerian-fashion-20.webp', 'A marigold and orange Ankara gele tied into a tall, structured crown.', '<p>Marigold yellow and orange Ankara print, tied into a tall, structured gele. Bright enough to lead any outfit.</p><p>Pair it with gold jewellery and a bold lip for weddings and celebrations.</p>'],
            ['Indigo Stripe Head Tie', 'dress-5-2', 'Women', 100_000, 'images/products/nigerian-fashion-10.webp', 'A blue and gold striped head tie, wrapped full and high.', '<p>Indigo with gold and black stripes, tied into a full, high crown. It matches our Indigo Stripe Wrap Set.</p><p>Wear it alone or with the wrap for a complete look.</p>'],
            ['Dress', 'dress', 'Men', 150_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-01-scaled.webp', '', ''],
            ['Dress', 'dress-10', 'Men', 170_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-02-scaled.webp', '', ''],
            ['Dress', 'dress-16', 'Men', 150_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-03-scaled.webp', '', ''],
            ['Dress', 'dress-17', 'Men', 165_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-04-scaled.webp', '', ''],
            ['Dress', 'dress-18', 'Men', 140_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-05-scaled.webp', '', ''],
            ['Dress', 'dress-19', 'Men', 175_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-06-scaled.webp', '', ''],
            ['Dress', 'dress-20', 'Men', 160_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-07-scaled.webp', '', ''],
            ['Dress', 'dress-21', 'Men', 145_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-08-scaled.webp', '', ''],
        ];

        foreach ($products as [$name, $slug, $category, $price, $image, $summary, $description]) {
            Product::create([
                'category_id' => $categories[$category]->id,
                'name' => $name,
                'slug' => $slug,
                'summary' => $summary,
                'description' => $description ?: null,
                'price_naira' => $price,
                'price_cents' => Product::usdCentsFor($price),
                'image' => $image,
                'in_stock' => true,
            ]);
        }

        foreach ([['Ify', '2348135438144'], ['Paschal', '2347031102089']] as $position => [$label, $number]) {
            WhatsappContact::firstOrCreate(['number' => $number], ['label' => $label, 'position' => $position]);
        }
    }
}
