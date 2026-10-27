<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppChatTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_pages_link_to_the_store_whatsapp_with_a_greeting(): void
    {
        config(['store.whatsapp' => '13015550100']);

        $this->get(route('home'))
            ->assertSee('Chat with us')
            ->assertSee('https://wa.me/13015550100?text='.rawurlencode('Hi Njikoka Fashions! I have a question.'), false);
    }

    public function test_product_pages_mention_the_product_in_the_message(): void
    {
        config(['store.whatsapp' => '13015550100']);

        $this->get(route('product', 'dress-7'))
            ->assertSee(rawurlencode("Hi Njikoka Fashions! I'm interested in Dress 7: ".route('product', 'dress-7')), false);
    }

    public function test_the_cart_can_be_sent_to_whatsapp_as_an_order_summary(): void
    {
        config(['store.whatsapp' => '13015550100']);
        [$dress7, $dress8] = Product::whereIn('slug', ['dress-7', 'dress-8'])->orderBy('slug')->get();

        $message = "Hi Njikoka Fashions! I'd like to order:\n\n"
            ."• Dress 7 × 2: $600.00\n  ".route('product', $dress7)."\n"
            ."• Dress 8 × 1: $285.00\n  ".route('product', $dress8)
            ."\n\nTotal: $885.00\n\nCould you help me complete this order?";

        $this->withSession(['cart' => [$dress7->id => 2, $dress8->id => 1]])
            ->get(route('cart'))
            ->assertSee('Order via WhatsApp')
            ->assertSee('https://wa.me/13015550100?text='.rawurlencode($message), false);
    }

    public function test_the_whatsapp_order_button_needs_items_in_the_cart(): void
    {
        config(['store.whatsapp' => '13015550100']);

        $this->get(route('cart'))->assertOk()->assertDontSee('Order via WhatsApp');
    }

    public function test_the_button_is_hidden_without_a_number(): void
    {
        config(['store.whatsapp' => '']);

        $this->get(route('home'))->assertOk()->assertDontSee('wa.me');
    }
}
