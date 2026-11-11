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
            ['Red George Double Wrapper Set', 'dress-1', 'Women', 190_000, 'images/products/igbo-red-george-double-wrapper.webp', 'An Igbo double wrapper in rich red George with a gold border, worn with a white puff-sleeve blouse and a matching red ichafu.', '<p>A classic Igbo look: two wrappers in red George, the top one finished with a gold border. It is worn with a crisp white uwe obi blouse with puffed sleeves and a sculpted red ichafu head tie.</p><p>Made for traditional weddings, igba nkwu and family celebrations. A matching red clutch completes the outfit.</p>'],
            ['Plum Lace Ball Gown', 'dress-2', 'Women', 200_000, 'images/products/nigerian-fashion-03.webp', 'A floor-length plum gown with sheer long sleeves and a full, sweeping skirt.', '<p>A deep plum ball gown with a fitted lace bodice, sheer long sleeves and a dramatic full skirt that pools at the floor. Pair it with a matching gele for a complete owambe look.</p><p>Made for receptions, engagements and evening celebrations.</p>'],
            ['Leopard Print Bubu Kaftan', 'dress-3', 'Women', 130_000, 'images/products/nigerian-fashion-05.webp', 'A relaxed, flowing bubu in a bold leopard print, shown in coral and navy.', '<p>This roomy bubu kaftan drapes loosely from the shoulders for easy, all-day comfort. The bold leopard print comes in coral and navy, each with a matching head wrap.</p><p>Dress it up with gold jewellery or keep it simple for everyday wear. Message us to choose your colour.</p>'],
            ['Garden Floral Ruffle Maxi', 'dress-5', 'Women', 170_000, 'images/products/nigerian-fashion-06.webp', 'A floor-length floral maxi dress with ruffled sleeves, styled with a teal head wrap.', '<p>A soft floral print on a neutral ground, with ruffled sleeves and a fitted waist that falls into a full-length skirt. The teal head wrap adds a pop of colour.</p><p>A graceful choice for garden parties, church and daytime events.</p>'],
            ['Akwete Double Wrapper Set', 'dress-6', 'Women', 170_000, 'images/products/igbo-akwete-double-wrapper.webp', 'An Igbo obiakwa double wrapper in handwoven-pattern Akwete, with a cream ruffled blouse and a pink ichafu.', '<p>Two Akwete wrappers in a bold pink, black and white woven pattern, worn as the traditional obiakwa double wrapper. It is paired with a cream blouse with sculpted ruffle sleeves and a soft pink ichafu.</p><p>An elegant choice for traditional weddings, church thanksgiving and cultural days.</p>'],
            ['Peach Lace Buba and Iro', 'dress-7', 'Women', 180_000, 'images/products/yoruba-peach-lace-buba-iro.webp', 'A Yoruba buba and iro in soft peach embroidered lace with wide, flowing sleeves.', '<p>A loose peach buba with wide, flowing sleeves and an embroidered finish, worn over a matching iro wrapper. Light and graceful, with room to move.</p><p>Wear it to engagements, owambe parties and naming ceremonies, with a gele or with your hair styled up.</p>'],
            ['Akwete Wrap Skirt Set', 'dress-8', 'Women', 150_000, 'images/products/igbo-akwete-wrap-skirt.webp', 'A multicolour Akwete wrap skirt with a draped panel, worn with a sheer-sleeved black top and an olive ichafu.', '<p>A fitted wrap skirt in colourful Akwete-pattern fabric, with a draped panel falling at the hip. It is worn with a black top with sheer long sleeves and an olive ichafu head tie.</p><p>A modern take on Igbo dressing, polished enough for events and easy to wear all day.</p>'],
            ['Coral Bead Print Wrapper Set', 'dress-9', 'Women', 120_000, 'images/products/coral-bead-print-wrapper.webp', 'A printed wrapper tied at the chest and waist, with a matching gele and layered coral beads.', '<p>A cream, brown and red print wrapper, tied at the chest with a second wrapper at the waist, and a matching gele. It is shown with layered red coral beads and a beaded armlet.</p><p>A striking look for cultural festivals, photoshoots and traditional ceremonies.</p>'],
            ['Isiagu Maiden Dress', 'dress-11', 'Women', 150_000, 'images/products/igbo-isiagu-maiden-dress.webp', 'A fitted Igbo maiden dress in red and gold Isiagu print, styled with coral beads.', '<p>A fitted short dress with an off-shoulder neckline, in the red and gold lion-head Isiagu print traditionally worn by Igbo royalty. It is styled with coral beads at the neck and ankles and a horsetail fan.</p><p>Made for igba nkwu, cultural days and celebrations where you want to honour tradition.</p>'],
            ['Lime Ankara Wrapper Set', 'dress-12', 'Women', 140_000, 'images/products/nigerian-fashion-04.webp', 'A lime and black Ankara wrapper set with a matching head tie and a blue shawl.', '<p>A lively lime, black and blue Ankara print, worn as a loose top and wrapper with a matching head tie. A royal blue shawl drapes over one shoulder.</p><p>Comfortable and colourful for daytime events and family visits.</p>'],
            ['Star Print Flare Dress', 'dress-13', 'Women', 130_000, 'images/products/igbo-star-print-flare-dress.webp', 'An orange star-print dress with a full flared skirt and ruffled off-shoulder sleeves, worn with an orange ichafu.', '<p>A bright orange dress covered in a red and black star print, with ruffled off-shoulder sleeves, a halter neck and a full, swinging skirt. It is worn with a matching orange ichafu.</p><p>Cheerful and easy to wear to parties, outings and Sunday lunches.</p>'],
            ['Emerald Sequin Wrapper Gown', 'dress-14', 'Women', 200_000, 'images/products/igbo-emerald-sequin-wrapper-gown.webp', 'A floor-length emerald gown in sequinned fabric with an off-shoulder ruffle neckline and a matching ichafu.', '<p>Deep emerald sequinned fabric shaped into a fitted, floor-length gown with nested wrapper layers and a dramatic off-shoulder ruffle. It is finished with a matching emerald ichafu, red coral beads and a beaded hand fan.</p><p>A showpiece for traditional weddings and evening receptions.</p>'],
            ['Etireti Madras Wrapper Set', 'dress-15', 'Women', 140_000, 'images/products/igbo-etireti-madras-wrapper.webp', 'A short etireti-style madras wrapper with a black off-shoulder top and coral beads.', '<p>A short wrapper in pink and green madras check, tied in the etireti style, with a structured black off-shoulder top. It is styled with coral beads at the neck and ankles and a horsetail whisk.</p><p>A youthful traditional look for cultural days, dance performances and celebrations.</p>'],
            ['Short Madras Wrapper Set with Coral Beads', 'dress-5-2', 'Women', 160_000, 'images/products/igbo-short-madras-wrapper-coral.webp', 'A red and black madras wrapper set with an off-shoulder top, finished with bold coral beads and a coral headpiece.', '<p>A red and black madras check wrapper and matching off-shoulder top, cinched at the waist with a beaded belt. It is styled with a full coral bead necklace and a coral headpiece.</p><p>Made for igba nkwu, maiden ceremonies and cultural festivals.</p>'],
            ['Tan Embroidered Short-Sleeve Senator', 'dress', 'Men', 150_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-01-scaled.webp', 'A camel-tan senator top and trousers with tonal embroidery on the chest.', '<p>A clean two-piece senator in warm camel tan, with short sleeves and a relaxed, straight cut that sits just below the hip.</p><ul><li>Round neck with a short concealed placket</li><li>Tonal embroidered motifs on both sides of the chest</li><li>Short sleeves and a straight hem</li><li>Matching slim-leg trousers</li></ul><p>Pair it with brown loafers and a watch for owambe parties, Friday wear and family events.</p>'],
            ['Olive Long Kaftan with Embroidered Placket', 'dress-10', 'Men', 170_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-02-scaled.webp', 'A knee-length olive kaftan with an embroidered placket and pocket, worn over matching trousers.', '<p>A knee-length kaftan in muted olive green with a band collar, three-quarter sleeves and an easy, flowing cut.</p><ul><li>Band collar with an embroidered button placket</li><li>Embroidered chest pocket</li><li>Three-quarter sleeves and side slits</li><li>Matching straight-leg trousers</li></ul><p>Shown with a burgundy fila and brown loafers. A polished choice for weddings, church and Jumat.</p>'],
            ['White Short-Sleeve Senator with Chest Embroidery', 'dress-16', 'Men', 150_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-03-scaled.webp', 'A crisp white senator with silver-grey embroidery on the chest and matching white trousers.', '<p>An all-white short-sleeve senator with fine silver-grey embroidery across both sides of the chest.</p><ul><li>Band collar with a short button placket</li><li>Embroidered chest panels and a chest pocket</li><li>Short sleeves and a straight hem</li><li>Matching white trousers</li></ul><p>Fresh and sharp for thanksgiving services, white-themed parties and summer events.</p>'],
            ['Grey Long-Sleeve Senator with Black Embroidery', 'dress-17', 'Men', 165_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-04-scaled.webp', 'A grey long-sleeve senator with black embroidery and buttons down the front.', '<p>A mid-grey senator top cut to the upper thigh, with long sleeves and contrasting black detail down the front.</p><ul><li>Round collar</li><li>Black embroidered motifs and buttons along the placket</li><li>Long sleeves and side slits</li><li>Matching tapered trousers</li></ul><p>Understated enough for the office and smart enough for meetings and evening events.</p>'],
            ['Beige Button-Front Senator Set', 'dress-18', 'Men', 140_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-05-scaled.webp', 'A beige long-sleeve senator with an offset dark-button placket and matching trousers.', '<p>A soft beige senator top with long sleeves and an offset button placket that gives a modern, clean line.</p><ul><li>Mandarin collar</li><li>Offset placket with dark contrast buttons</li><li>Long sleeves and a thigh-length hem</li><li>Matching straight trousers</li></ul><p>An easy everyday outfit that works for work, church and casual Fridays.</p>'],
            ['White Long Kaftan with Patterned Pocket', 'dress-19', 'Men', 175_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-06-scaled.webp', 'A long white kaftan with a black and white patterned chest pocket, worn over white trousers.', '<p>A knee-length white kaftan with long sleeves and a single patterned chest pocket for contrast.</p><ul><li>Band collar with a concealed placket</li><li>Black and white patterned chest pocket</li><li>Long sleeves and side slits</li><li>Matching white trousers</li></ul><p>Shown with an embroidered cap. An elegant choice for weddings, thanksgiving services and Eid.</p>'],
            ['White Senator with Navy Embroidered Placket', 'dress-20', 'Men', 160_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-07-scaled.webp', 'A white long-sleeve senator with navy embroidery down the placket and a framed chest pocket.', '<p>A white senator top with long sleeves, finished with a column of navy embroidery down the placket and a navy-framed chest pocket.</p><ul><li>Round collar</li><li>Navy embroidered placket</li><li>Navy-framed chest pocket</li><li>Long sleeves and a thigh-length hem</li><li>Matching white trousers</li></ul><p>A standout look for weddings, birthdays and special occasions.</p>'],
            ['Sky Blue Senator with Navy Piping', 'dress-21', 'Men', 145_000, 'images/products/Patrick-u-Patrick-02-_2000-2000-08-scaled.webp', 'A short-sleeved sky blue senator with navy piping along the collar, placket and pocket.', '<p>A bright sky blue senator top with short sleeves and fine navy piping that traces the collar, the curved placket and the chest pocket.</p><ul><li>Round collar with navy piping</li><li>Curved, piped placket and pocket</li><li>Short sleeves and a thigh-length hem</li><li>Matching straight trousers</li></ul><p>Shown with a patterned cap. A cheerful choice for daytime events, outings and Sunday wear.</p>'],
        ];

        /** @var array<string, array{0: string, 1: string}> $photoCredits Credit line and source page for photos whose licence requires attribution. */
        $photoCredits = [
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
                'image_credit' => $photoCredits[$slug][0] ?? null,
                'image_credit_url' => $photoCredits[$slug][1] ?? null,
                'in_stock' => true,
            ]);
        }

        foreach ([['Ify', '2348135438144'], ['Paschal', '2347031102089']] as $position => [$label, $number]) {
            WhatsappContact::firstOrCreate(['number' => $number], ['label' => $label, 'position' => $position]);
        }
    }
}
