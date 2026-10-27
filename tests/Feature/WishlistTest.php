<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_the_heart_saves_a_product_and_shows_it_on_the_favorites_page(): void
    {
        $product = Product::where('slug', 'dress-7')->first();

        $this->from(route('shop'))
            ->post(route('wishlist.toggle', $product))
            ->assertRedirect(route('shop'))
            ->assertSessionHas('status', 'Dress 7 was added to your favorites.');

        $this->get(route('wishlist'))
            ->assertOk()
            ->assertSee('Dress 7')
            ->assertSee('aria-pressed="true"', false)
            ->assertSee('Favorites, 1 item');
    }

    public function test_pressing_the_heart_again_removes_the_product(): void
    {
        $product = Product::where('slug', 'dress-7')->first();

        $this->withSession(['wishlist' => [$product->id]])
            ->postJson(route('wishlist.toggle', $product))
            ->assertExactJson(['saved' => false, 'count' => 0, 'message' => 'Dress 7 was removed from your favorites.']);

        $this->get(route('wishlist'))->assertSee("You haven't saved any favorites yet.", false);
    }

    public function test_saving_by_javascript_returns_the_new_state(): void
    {
        $product = Product::where('slug', 'dress-7')->first();

        $this->postJson(route('wishlist.toggle', $product))
            ->assertExactJson(['saved' => true, 'count' => 1, 'message' => 'Dress 7 was added to your favorites.']);

        $this->assertSame([$product->id], session('wishlist'));
    }

    public function test_favorites_already_in_the_cart_link_to_the_cart_instead_of_adding_again(): void
    {
        $inCart = Product::where('slug', 'dress-7')->first();
        $notInCart = Product::where('slug', 'dress-8')->first();

        $this->withSession(['wishlist' => [$inCart->id, $notInCart->id], 'cart' => [$inCart->id => 1]])
            ->get(route('wishlist'))
            ->assertSee('In your cart')
            ->assertSee(route('cart.store', $notInCart))
            ->assertDontSee(route('cart.store', $inCart));
    }

    public function test_the_cart_suggests_in_stock_favorites_that_are_not_in_it(): void
    {
        [$inCart, $saved, $soldOut] = Product::whereIn('slug', ['dress-7', 'dress-8', 'dress-9'])->orderBy('slug')->get();
        $soldOut->update(['in_stock' => false]);

        $this->withSession(['wishlist' => [$inCart->id, $saved->id, $soldOut->id], 'cart' => [$inCart->id => 1]])
            ->get(route('cart'))
            ->assertSee('From your favorites')
            ->assertSee(route('cart.store', $saved))
            ->assertDontSee('Dress 9');
    }

    public function test_flash_messages_show_as_a_dismissable_toast(): void
    {
        $this->withSession(['status' => 'Saved!'])
            ->get(route('shop'))
            ->assertSee('Saved!')
            ->assertSee('aria-label="Dismiss message"', false);
    }
}
