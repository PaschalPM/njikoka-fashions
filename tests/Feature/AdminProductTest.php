<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
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
            ->assertSee('Dress 12')
            ->assertSee('$285.00');
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

        $this->get(route('admin.products.index', ['q' => 'Dress 1']))
            ->assertSee('Dress 12')
            ->assertDontSee('Dress 9');

        $this->get(route('admin.products.index', ['category' => Category::where('slug', 'men')->value('id')]))
            ->assertSee('$260.00')
            ->assertDontSee('Dress 12');

        $this->get(route('admin.products.index', ['stock' => 'out']))
            ->assertSee('Dress 12')
            ->assertDontSee('Dress 11');
    }

    public function test_admin_sees_the_add_and_edit_forms(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.products.create'))->assertOk()->assertSee('Add product');
        $this->get(route('admin.products.edit', 'dress-7'))->assertOk()->assertSee('value="300.00"', false);
    }

    public function test_admin_can_add_a_product_with_a_photo(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.products.store'), $this->productFields())
            ->assertRedirect(route('admin.products.index'));

        $product = Product::where('name', 'Ankara Wrap Dress')->sole();
        $this->assertSame(14999, $product->price_cents);
        $this->assertSame('ankara-wrap-dress', $product->slug);
        $this->assertTrue($product->in_stock);
        Storage::disk('public')->assertExists(str($product->image)->after('storage/')->value());

        $this->get(route('product', $product))->assertOk()->assertSee('$149.99');
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
            ->post(route('admin.products.store'), $this->productFields(['image' => null, 'price' => '12.345']))
            ->assertSessionHasErrors([
                'image' => 'The image field is required.',
                'price' => 'The price field must have 0-2 decimal places.',
            ]);
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
            ->put(route('admin.products.update', $product), $this->productFields(['image' => null, 'price' => '310', 'in_stock' => null]))
            ->assertRedirect(route('admin.products.index'));

        $product->refresh();
        $this->assertSame(31000, $product->price_cents);
        $this->assertFalse($product->in_stock);
        $this->assertSame('images/products/Patrick-U-Patrick_R1-01-scaled.jpg', $product->image);
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
            'price' => '149.99',
            'summary' => '<p>Hand-tailored wrap dress.</p>',
            'description' => '',
            'image' => UploadedFile::fake()->image('dress.jpg', 800, 800),
            'in_stock' => '1',
        ], $overrides);
    }
}
