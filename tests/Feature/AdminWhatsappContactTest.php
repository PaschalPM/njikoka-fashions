<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WhatsappClick;
use App\Models\WhatsappContact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWhatsappContactTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_customers_cannot_change_the_whatsapp_numbers(): void
    {
        $this->actingAs(User::factory()->create())
            ->put(route('admin.whatsapp.update'), ['contacts' => [['label' => 'Sales', 'number' => '13015550100']]])
            ->assertForbidden();

        $this->assertDatabaseMissing('whatsapp_contacts', ['number' => '13015550100']);
    }

    public function test_admin_saves_three_numbers_in_order_and_formatting_is_stripped(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.whatsapp.update'), ['contacts' => [
                ['label' => 'Sales', 'number' => '+1 (301) 555-0100'],
                ['label' => 'Custom designs', 'number' => '+234 803 555 0101'],
                ['label' => 'Customer care', 'number' => '13015550102'],
            ]])
            ->assertRedirect(route('admin.whatsapp.edit'))
            ->assertSessionHasNoErrors();

        $this->assertSame(
            [['Sales', '13015550100'], ['Custom designs', '2348035550101'], ['Customer care', '13015550102']],
            WhatsappContact::orderBy('position')->get()->map(fn ($contact) => [$contact->label, $contact->number])->all(),
        );
    }

    public function test_saving_replaces_old_numbers_and_skips_empty_slots(): void
    {
        WhatsappContact::factory()->create(['label' => 'Old line', 'number' => '13015550199']);

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.whatsapp.update'), ['contacts' => [
                ['label' => '', 'number' => ''],
                ['label' => 'Sales', 'number' => '13015550100'],
                ['label' => '', 'number' => ''],
            ]])
            ->assertSessionHasNoErrors();

        $this->assertSame(['Sales'], WhatsappContact::pluck('label')->all());
        $this->assertSame(0, WhatsappContact::first()->position);
    }

    public function test_a_number_needs_a_label(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.whatsapp.update'), ['contacts' => [['label' => '', 'number' => '13015550100']]])
            ->assertSessionHasErrors(['contacts.0.label' => 'Give WhatsApp number 1 a label, e.g. Sales.']);
    }

    public function test_a_number_must_include_a_country_code_length(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.whatsapp.update'), ['contacts' => [['label' => 'Sales', 'number' => '555-01']]])
            ->assertSessionHasErrors(['contacts.0.number' => 'WhatsApp number 1 must be 8 to 15 digits, starting with the country code.']);
    }

    public function test_customers_cannot_see_whatsapp_activity(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.whatsapp.index'))
            ->assertForbidden();
    }

    public function test_activity_counts_people_and_chats_per_contact(): void
    {
        WhatsappClick::factory()->count(2)->create(['visitor' => 'a5f3c1e2-0000-4000-8000-000000000001']);
        WhatsappClick::factory()->order()->create(['visitor' => 'a5f3c1e2-0000-4000-8000-000000000002']);
        WhatsappClick::factory()->create(['contact_label' => 'Paschal', 'contact_number' => '2347031102089']);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.whatsapp.index'))
            ->assertOk()
            ->assertViewHas('totals', ['clicks' => 4, 'people' => 3, 'chats' => 3, 'orders' => 1])
            ->assertViewHas('byContact', fn ($rows) => $rows->map(fn ($row) => [$row->label, (int) $row->clicks, (int) $row->people, (int) $row->orders])->all()
                === [['Ify', 3, 2, 1], ['Paschal', 1, 1, 0]])
            ->assertSee('1 item, $300.00');
    }

    public function test_activity_can_be_limited_to_the_last_seven_days(): void
    {
        WhatsappClick::factory()->create();
        WhatsappClick::factory()->create(['created_at' => now()->subDays(8)]);
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.whatsapp.index', ['period' => '7']))->assertViewHas('totals', fn (array $totals) => $totals['clicks'] === 1);
        $this->get(route('admin.whatsapp.index', ['period' => 'all']))->assertViewHas('totals', fn (array $totals) => $totals['clicks'] === 2);
    }
}
