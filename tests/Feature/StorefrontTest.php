<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Stripe\Checkout\Session;
use Stripe\Service\Checkout\CheckoutServiceFactory;
use Stripe\Service\Checkout\SessionService;
use Stripe\StripeClient;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_public_pages_render(): void
    {
        foreach (['home', 'shop', 'about', 'contact', 'faqs', 'shipping', 'returns', 'custom-designs', 'book-appointment', 'cart', 'account'] as $route) {
            $this->get(route($route))->assertOk();
        }

        $this->get(route('product', 'dress-1'))->assertOk()->assertSee('₦190,000')->assertSee('$152')
            ->assertSee('Prices include materials, workmanship and delivery.');
    }

    public function test_home_page_uses_the_original_copy_and_contact_details(): void
    {
        $this->get(route('home'))
            ->assertSee('Fashion Made to Celebrate Culture, Color, and Confidence')
            ->assertSee('africanconfluence@gmail.com')
            ->assertSee('14502 Greenview Drive Laurel, Maryland 20708');
    }

    public function test_shop_filters_by_category(): void
    {
        $this->get(route('shop', ['categories' => ['women'], 'q' => 'Set']))
            ->assertOk()
            ->assertSee('Akwete Wrap Skirt Set')
            ->assertDontSee('Beige Button-Front Senator Set');
    }

    public function test_the_shop_filters_by_style(): void
    {
        $this->get(route('shop', ['styles' => ['babban-riga']]))
            ->assertOk()
            ->assertSee('Sky Blue Babban Riga with Geometric Embroidery')
            ->assertSee('Ivory Babban Riga with Embroidered Front')
            ->assertDontSee('Grey Long-Sleeve Senator with Black Embroidery');
    }

    #[DataProvider('babbanRigaSpellings')]
    public function test_searching_another_spelling_of_a_style_selects_that_style(string $search): void
    {
        $this->get(route('shop', ['q' => $search]))
            ->assertOk()
            ->assertSee('Sky Blue Babban Riga with Geometric Embroidery')
            ->assertDontSee('Grey Long-Sleeve Senator with Black Embroidery');
    }

    /**
     * @return array<string, array{string}>
     */
    public static function babbanRigaSpellings(): array
    {
        return ['hyphenated' => ['baban-riga'], 'one word' => ['Babanriga'], 'babbar' => ['babbar riga']];
    }

    public function test_style_chips_show_counts_and_hide_styles_with_no_products(): void
    {
        $this->get(route('shop'))
            ->assertSee('Shop by style', false)
            ->assertSee(route('shop', ['styles' => ['babban-riga']]), false)
            ->assertDontSee(route('shop', ['styles' => ['agbada']]), false);
    }

    public function test_free_text_search_also_matches_descriptions(): void
    {
        $this->get(route('shop', ['q' => 'aso ebi']))
            ->assertSee('Silver Lace Mermaid Gown with Gele')
            ->assertDontSee('Sky Blue Babban Riga with Geometric Embroidery');
    }

    public function test_product_pages_credit_photos_that_need_attribution(): void
    {
        Product::where('slug', 'dress-7')->update(['image_credit' => 'Photo: Ada Obi, CC BY-SA 4.0', 'image_credit_url' => 'https://example.com/photo']);

        $this->get(route('product', 'dress-7'))
            ->assertOk()
            ->assertSee('Photo: Ada Obi, CC BY-SA 4.0')
            ->assertSee('href="https://example.com/photo"', false);

        $this->get(route('product', 'dress-1'))->assertOk()->assertDontSee('<figcaption', false);
    }

    public function test_the_shop_and_product_pages_offer_any_nigerian_attire_made_to_order(): void
    {
        $this->get(route('shop'))
            ->assertSee('Don’t see your style? We’ll make it.')
            ->assertSee('Evening gowns')
            ->assertSee(route('custom-designs'))
            ->assertSee(route('whatsapp.chat', '2348135438144'));

        $this->get(route('shop', ['q' => 'no such outfit']))->assertSee('Can’t find it? We can make it for you.');

        $this->get(route('product', 'dress-7'))->assertSee('We make any Nigerian attire, plus gowns, suits and other styles.');
    }

    public function test_products_can_be_added_to_the_cart(): void
    {
        $product = Product::where('slug', 'dress-7')->first();

        $this->post(route('cart.store', $product), ['quantity' => 2])->assertRedirect();

        $this->get(route('cart'))->assertSee('Peach Lace Buba and Iro')->assertSee('₦360,000')->assertSee('$288 by card');
    }

    public function test_checkout_shows_the_naira_total_and_the_dollar_card_charge(): void
    {
        $product = Product::where('slug', 'dress-7')->first();

        $this->withSession(['cart' => [$product->id => 2]])
            ->get(route('checkout'))
            ->assertOk()
            ->assertSeeInOrder(['Total', '₦360,000', 'Charged by card in US dollars', '$288']);
    }

    public function test_checkout_creates_an_order_and_redirects_to_stripe(): void
    {
        $product = Product::where('slug', 'dress-7')->first();
        $sessions = $this->fakeStripeSessions();

        $sessions->shouldReceive('create')->once()
            ->withArgs(fn ($params) => $params['line_items'][0]['price_data']['unit_amount'] === 14400
                && $params['line_items'][0]['quantity'] === 2)
            ->andReturn(Session::constructFrom(['id' => 'cs_test_123', 'url' => 'https://checkout.stripe.com/c/pay/cs_test_123']));

        $this->withSession(['cart' => [$product->id => 2]])
            ->post(route('checkout.store'), $this->billingDetails())
            ->assertRedirect('https://checkout.stripe.com/c/pay/cs_test_123');

        $order = Order::with('items')->sole();
        $this->assertSame(28800, $order->total_cents);
        $this->assertSame('cs_test_123', $order->stripe_session_id);
        $this->assertSame('pending', $order->status);
        $this->assertSame(2, $order->items->first()->quantity);
    }

    public function test_successful_payment_marks_the_order_paid_and_clears_the_cart(): void
    {
        $order = Order::create([...$this->billingDetails(), 'total_cents' => 2000, 'stripe_session_id' => 'cs_test_paid']);

        $this->fakeStripeSessions()->shouldReceive('retrieve')->once()->with('cs_test_paid')
            ->andReturn(Session::constructFrom(['id' => 'cs_test_paid', 'payment_status' => 'paid', 'payment_intent' => 'pi_123']));

        $this->withSession(['cart' => [1 => 1]])
            ->get(route('checkout.success', ['session_id' => 'cs_test_paid']))
            ->assertOk()
            ->assertSee('Your order has been received')
            ->assertSessionMissing('cart');

        $this->assertTrue($order->fresh()->isPaid());
        $this->assertSame('processing', $order->fresh()->status);
    }

    public function test_stripe_webhook_marks_the_order_paid(): void
    {
        config(['services.stripe.webhook_secret' => 'whsec_test']);
        $order = Order::create([...$this->billingDetails(), 'total_cents' => 2000, 'stripe_session_id' => 'cs_test_hook']);

        $payload = json_encode([
            'id' => 'evt_1',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => ['object' => ['id' => 'cs_test_hook', 'object' => 'checkout.session', 'payment_status' => 'paid', 'payment_intent' => 'pi_456']],
        ]);
        $timestamp = time();
        $signature = 't='.$timestamp.',v1='.hash_hmac('sha256', "{$timestamp}.{$payload}", 'whsec_test');

        $this->call('POST', route('stripe.webhook'), [], [], [], ['HTTP_STRIPE_SIGNATURE' => $signature, 'CONTENT_TYPE' => 'application/json'], $payload)
            ->assertOk();

        $this->assertSame('pi_456', $order->fresh()->stripe_payment_intent);
        $this->assertTrue($order->fresh()->isPaid());

        $this->call('POST', route('stripe.webhook'), [], [], [], ['HTTP_STRIPE_SIGNATURE' => 't=1,v1=bad', 'CONTENT_TYPE' => 'application/json'], $payload)
            ->assertStatus(400);
    }

    public function test_customers_can_log_in_and_see_their_orders(): void
    {
        $user = User::factory()->create(['email' => 'ada@example.com']);
        $order = Order::create([...$this->billingDetails(), 'user_id' => $user->id, 'total_cents' => 2000]);

        $this->post(route('login'), ['email' => 'ada@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post(route('login'), ['email' => 'ada@example.com', 'password' => 'password'])->assertRedirect(route('account'));

        $this->get(route('account'))->assertSee($order->number);
        $this->get(route('account.order', $order))->assertOk();
    }

    public function test_customers_can_reset_a_lost_password(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'ada@example.com']);

        $this->get(route('password.request'))->assertOk();
        $this->post(route('password.email'), ['email' => 'ada@example.com'])->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) {
            $this->post(route('password.update'), [
                'token' => $notification->token,
                'email' => 'ada@example.com',
                'password' => 'new-secret-password',
                'password_confirmation' => 'new-secret-password',
            ])->assertRedirect(route('account'));

            return true;
        });

        $this->post(route('login'), ['email' => 'ada@example.com', 'password' => 'new-secret-password'])->assertRedirect(route('account'));
    }

    public function test_customers_cannot_view_other_customers_orders(): void
    {
        $order = Order::create([...$this->billingDetails(), 'user_id' => User::factory()->create()->id, 'total_cents' => 2000]);

        $this->actingAs(User::factory()->create())->get(route('account.order', $order))->assertNotFound();
    }

    public function test_reviews_can_be_left_on_products(): void
    {
        $this->post(route('reviews.store', 'dress-1'), [
            'rating' => 5,
            'comment' => 'Beautiful fabric.',
            'author' => 'Ada',
            'email' => 'ada@example.com',
        ])->assertRedirect();

        $this->get(route('product', 'dress-1'))->assertSee('Reviews (1)')->assertSee('Beautiful fabric.');
    }

    public function test_contact_form_is_validated_and_stored(): void
    {
        $this->post(route('contact.store'), [])->assertSessionHasErrors(['email', 'message'])->assertSessionDoesntHaveErrors(['first_name', 'subject']);

        $this->post(route('contact.store'), [
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => 'ada@example.com',
            'subject' => 'Sizing',
            'message' => 'Do you have this in a medium?',
        ])->assertSessionHas('status');

        $this->assertDatabaseHas('contact_messages', ['email' => 'ada@example.com']);
    }

    private function billingDetails(): array
    {
        return [
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => 'ada@example.com',
            'address_line1' => '1 Main St',
            'city' => 'Laurel',
            'state' => 'MD',
            'postcode' => '20708',
            'country' => 'US',
        ];
    }

    private function fakeStripeSessions(): Mockery\MockInterface
    {
        $sessions = Mockery::mock(SessionService::class);
        $checkout = Mockery::mock(CheckoutServiceFactory::class);
        $checkout->sessions = $sessions;
        $stripe = Mockery::mock(StripeClient::class);
        $stripe->checkout = $checkout;
        $this->app->instance(StripeClient::class, $stripe);

        return $sessions;
    }
}
