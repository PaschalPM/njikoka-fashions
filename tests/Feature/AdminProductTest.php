<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_guests_are_sent_to_the_login_page(): void
    {
        $this->get(route('admin.products.index'))->assertRedirect(route('account'));
    }

    public function test_customers_cannot_manage_products(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('admin.products.store'), $this->productFields())
            ->assertForbidden();

        $this->assertDatabaseMissing('products', ['name' => 'Ankara Wrap Dress']);
    }

    public function test_admin_sees_the_product_list(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Lime Ankara Wrapper Set')
            ->assertSee('₦140,000')
            ->assertSee('$112');
    }

    public function test_admin_home_opens_the_product_list(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.home'))
            ->assertRedirect(route('admin.products.index'));
    }

    public function test_admin_can_filter_products_by_name_category_and_stock(): void
    {
        Product::where('slug', 'dress-12')->update(['in_stock' => false]);
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.products.index', ['q' => 'Lime']))
            ->assertSee('Lime Ankara Wrapper Set')
            ->assertDontSee('Coral Bead Print Wrapper Set');

        $this->get(route('admin.products.index', ['category' => Category::where('slug', 'men')->value('id')]))
            ->assertSee('₦175,000')
            ->assertDontSee('Lime Ankara Wrapper Set');

        $this->get(route('admin.products.index', ['stock' => 'out']))
            ->assertSee('Lime Ankara Wrapper Set')
            ->assertDontSee('Isiagu Maiden Dress');
    }

    public function test_admin_sees_the_add_and_edit_forms(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.products.create'))->assertOk()->assertSee('Add product');
        $this->get(route('admin.products.edit', 'dress-7'))->assertOk()->assertSee('value="180000"', false);
    }

    public function test_admin_can_add_a_product_with_a_photo(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.products.store'), $this->productFields())
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Ankara Wrap Dress')->sole();
        $this->assertSame(150000, $product->price_naira);
        $this->assertSame(12000, $product->price_cents);
        $this->assertSame('ankara-wrap-dress', $product->slug);
        $this->assertTrue($product->in_stock);
        Storage::disk('public')->assertExists(str($product->image)->after('storage/')->value());

        $this->get(route('product', $product))->assertOk()->assertSee('₦150,000')->assertSee('$120');
    }

    public function test_changing_the_exchange_rate_reprices_dollar_amounts(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.exchange-rate.update'), ['ngn_per_usd' => 1600, 'usd_markup_percent' => 25])
            ->assertRedirect()
            ->assertSessionHas('status', 'Saved: ₦1,600 = $1, plus 25%. Dollar prices were updated.');

        $product = Product::firstWhere('slug', 'dress-2');
        $this->assertSame(200000, $product->price_naira);
        $this->assertSame(15600, $product->price_cents);
        $this->assertSame([1600, 25], [Setting::ngnPerUsd(), Setting::usdMarkupPercent()]);
    }

    public function test_dollar_prices_carry_a_20_percent_markup_by_default(): void
    {
        $this->assertSame(16000, Product::firstWhere('slug', 'dress-2')->price_cents);
        $this->assertSame(12000, Product::usdCentsFor(150000, 1500));
        $this->assertSame(10000, Product::usdCentsFor(150000, 1500, 0));
    }

    public function test_customers_cannot_change_the_exchange_rate(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.exchange-rate.update'), ['ngn_per_usd' => 1600, 'usd_markup_percent' => 0])
            ->assertForbidden();

        $this->assertSame(16000, Product::firstWhere('slug', 'dress-2')->price_cents);
    }

    public function test_the_exchange_rate_and_markup_must_be_in_range(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.exchange-rate.update'), ['ngn_per_usd' => 5, 'usd_markup_percent' => -5])
            ->assertSessionHasErrors([
                'ngn_per_usd' => 'The exchange rate field must be at least 100.',
                'usd_markup_percent' => 'The dollar markup field must be at least 0.',
            ]);
    }

    public function test_admin_can_credit_a_photo_and_the_link_must_be_a_web_address(): void
    {
        Storage::fake('public');
        $this->actingAs(User::factory()->admin()->create());

        $this->post(route('admin.products.store'), $this->productFields(['image_credit' => 'Photo: Ada Obi, CC BY 4.0', 'image_credit_url' => 'javascript:alert(1)']))
            ->assertSessionHasErrors(['image_credit_url' => 'The image credit url field must be a valid URL.']);

        $this->post(route('admin.products.store'), $this->productFields(['image_credit' => 'Photo: Ada Obi, CC BY 4.0', 'image_credit_url' => 'https://example.com/photo']))
            ->assertSessionHasNoErrors();

        $product = Product::where('name', 'Ankara Wrap Dress')->sole();
        $this->assertSame(['Photo: Ada Obi, CC BY 4.0', 'https://example.com/photo'], [$product->image_credit, $product->image_credit_url]);
    }

    public function test_new_products_get_a_unique_slug(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.products.store'), $this->productFields(['name' => 'Dress 1']));

        $this->assertSame('dress-1-2', Product::where('name', 'Dress 1')->latest('id')->first()->slug);
    }

    public function test_a_photo_and_valid_price_are_required(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.products.store'), $this->productFields(['image' => null, 'price' => '150000.50']))
            ->assertSessionHasErrors([
                'image' => 'The image field is required.',
                'price' => 'The price field must be an integer.',
            ]);
    }

    public function test_large_photos_are_stored_as_webp_no_wider_than_1600px(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.products.store'), $this->productFields(['image' => UploadedFile::fake()->image('phone-photo.jpg', 3000, 2000)]));

        $path = str(Product::where('name', 'Ankara Wrap Dress')->sole()->image)->after('storage/')->value();
        $this->assertStringEndsWith('.webp', $path);
        $this->assertSame([1600, 1067], array_slice(getimagesizefromstring(Storage::disk('public')->get($path)), 0, 2));
    }

    public function test_uploads_must_be_images(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.products.store'), $this->productFields(['image' => UploadedFile::fake()->create('dress.pdf', 100, 'application/pdf')]))
            ->assertSessionHasErrors('image');
    }

    public function test_admin_can_change_the_price_and_mark_sold_out_without_a_new_photo(): void
    {
        $product = Product::where('slug', 'dress-7')->first();

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.products.update', $product), $this->productFields(['image' => null, 'price' => '195000', 'in_stock' => null]))
            ->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame(195000, $product->price_naira);
        $this->assertSame(15600, $product->price_cents);
        $this->assertFalse($product->in_stock);
        $this->assertSame('images/products/yoruba-peach-lace-buba-iro.webp', $product->image);
        $this->assertSame('dress-7', $product->slug);
    }

    public function test_replacing_a_photo_deletes_the_old_upload(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.products.store'), $this->productFields());
        $product = Product::where('name', 'Ankara Wrap Dress')->sole();
        $oldPath = str($product->image)->after('storage/')->value();

        $this->put(route('admin.products.update', $product), $this->productFields(['image' => UploadedFile::fake()->image('new.png')]));

        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists(str($product->refresh()->image)->after('storage/')->value());
    }

    public function test_admin_can_delete_a_product(): void
    {
        $product = Product::where('slug', 'dress-7')->first();

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertModelMissing($product);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function productFields(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Ankara Wrap Dress',
            'category_id' => Category::where('slug', 'women')->value('id'),
            'price' => '150000',
            'summary' => '<p>Hand-tailored wrap dress.</p>',
            'description' => '',
            'image' => UploadedFile::fake()->image('dress.jpg', 800, 800),
            'in_stock' => '1',
        ], $overrides);
    }
}
