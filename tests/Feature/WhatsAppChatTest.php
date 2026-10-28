<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\WhatsappClick;
use App\Models\WhatsappContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppChatTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_the_seeded_shop_offers_ify_and_paschal(): void
    {
        $this->get(route('home'))
            ->assertSee('Chat with us')
            ->assertSeeInOrder(['Ify', '+2348135438144', 'Paschal', '+2347031102089'])
            ->assertSee(route('whatsapp.chat', '2348135438144'))
            ->assertSee(route('whatsapp.chat', '2347031102089'));
    }

    public function test_chat_opens_whatsapp_with_a_greeting_and_is_recorded(): void
    {
        $this->get(route('whatsapp.chat', '2348135438144'))
            ->assertRedirect('https://wa.me/2348135438144?text='.rawurlencode('Hi Njikoka Fashions! I have a question.'))
            ->assertCookie('wa_visitor');

        $click = WhatsappClick::sole();
        $this->assertSame(['Ify', '2348135438144', 'chat'], [$click->contact_label, $click->contact_number, $click->source]);
    }

    public function test_product_pages_mention_the_product_in_the_message(): void
    {
        $product = Product::firstWhere('slug', 'dress-7');

        $this->get(route('product', $product))->assertSee(route('whatsapp.chat', ['2348135438144', 'product' => 'dress-7']), false);

        $this->get(route('whatsapp.chat', ['2348135438144', 'product' => 'dress-7']))
            ->assertRedirect('https://wa.me/2348135438144?text='.rawurlencode("Hi Njikoka Fashions! I'm interested in Dress 7: ".route('product', 'dress-7')));

        $this->assertSame($product->id, WhatsappClick::sole()->product_id);
    }

    public function test_a_number_the_shop_does_not_offer_is_not_found(): void
    {
        $this->get(route('whatsapp.chat', '13015550100'))->assertNotFound();

        $this->assertDatabaseCount('whatsapp_clicks', 0);
    }

    public function test_repeat_clicks_from_one_visitor_keep_the_same_visitor_id(): void
    {
        $this->get(route('whatsapp.chat', '2348135438144'));
        $visitor = WhatsappClick::sole()->visitor;

        $this->withCookie('wa_visitor', $visitor)->get(route('whatsapp.chat', '2347031102089'));

        $this->assertSame([$visitor], WhatsappClick::distinct()->pluck('visitor')->all());
    }

    public function test_signed_in_customers_are_linked_to_their_click(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)->get(route('whatsapp.chat', '2348135438144'));

        $this->assertSame($customer->id, WhatsappClick::sole()->user_id);
    }

    public function test_the_cart_offers_every_contact_for_the_order(): void
    {
        $product = Product::firstWhere('slug', 'dress-7');

        $this->withSession(['cart' => [$product->id => 1]])
            ->get(route('cart'))
            ->assertSee('Who would you like to order with?')
            ->assertSee(route('whatsapp.order', '2348135438144'))
            ->assertSee(route('whatsapp.order', '2347031102089'));
    }

    public function test_the_cart_is_sent_to_whatsapp_as_an_order_summary_and_recorded(): void
    {
        [$dress7, $dress8] = Product::whereIn('slug', ['dress-7', 'dress-8'])->orderBy('slug')->get();

        $message = "Hi Njikoka Fashions! I'd like to order:\n\n"
            ."• Dress 7 × 2: $600.00\n  ".route('product', $dress7)."\n"
            ."• Dress 8 × 1: $285.00\n  ".route('product', $dress8)
            ."\n\nTotal: $885.00\n\nCould you help me complete this order?";

        $this->withSession(['cart' => [$dress7->id => 2, $dress8->id => 1]])
            ->get(route('whatsapp.order', '2347031102089'))
            ->assertRedirect('https://wa.me/2347031102089?text='.rawurlencode($message));

        $click = WhatsappClick::sole();
        $this->assertSame(['Paschal', 'order', 3, 88500], [$click->contact_label, $click->source, $click->cart_items, $click->cart_total_cents]);
    }

    public function test_an_order_with_an_empty_cart_goes_back_to_the_cart(): void
    {
        $this->get(route('whatsapp.order', '2348135438144'))->assertRedirect(route('cart'));

        $this->assertDatabaseCount('whatsapp_clicks', 0);
    }

    public function test_the_whatsapp_order_button_needs_items_in_the_cart(): void
    {
        $this->get(route('cart'))->assertOk()->assertDontSee('Order via WhatsApp');
    }

    public function test_the_default_number_is_used_until_contacts_are_saved(): void
    {
        WhatsappContact::query()->delete();
        config(['store.whatsapp' => '13015550100']);

        $this->get(route('home'))->assertSee(route('whatsapp.chat', '13015550100'));
        $this->get(route('whatsapp.chat', '13015550100'))->assertRedirect('https://wa.me/13015550100?text='.rawurlencode('Hi Njikoka Fashions! I have a question.'));
    }

    public function test_the_button_is_hidden_without_a_number(): void
    {
        WhatsappContact::query()->delete();
        config(['store.whatsapp' => '']);

        $this->get(route('home'))->assertOk()->assertDontSee('Chat with us');
    }
}
