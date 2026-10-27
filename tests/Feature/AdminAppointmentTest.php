<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_a_booked_appointment_appears_in_the_admin_as_new(): void
    {
        $this->post(route('book-appointment.store'), [
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => 'ada@example.com',
            'service' => config('store.services')[0],
            'preferred_date' => today()->addDays(3)->toDateString(),
            'time_slot' => '11:00 AM',
            'notes' => 'Fitting for a wedding outfit.',
            'policy' => '1',
        ])->assertSessionHasNoErrors();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.appointments.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('11:00 AM')
            ->assertSee(config('store.services')[0])
            ->assertSee('1<span class="sr-only"> new</span>', false);
    }

    public function test_customers_cannot_see_appointments(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.appointments.index'))
            ->assertForbidden();
    }

    public function test_upcoming_and_past_appointments_are_listed_separately(): void
    {
        $this->appointment(['first_name' => 'Soon', 'preferred_date' => today()->addDay()]);
        $this->appointment(['first_name' => 'Earlier', 'preferred_date' => today()->subDay()]);
        $this->actingAs(User::factory()->admin()->create());

        $this->get(route('admin.appointments.index'))->assertSee('Soon Obi')->assertDontSee('Earlier Obi');
        $this->get(route('admin.appointments.index', ['when' => 'past']))->assertSee('Earlier Obi')->assertDontSee('Soon Obi');
    }

    public function test_upcoming_appointments_are_soonest_first(): void
    {
        $this->appointment(['first_name' => 'Later', 'preferred_date' => today()->addDays(5)]);
        $this->appointment(['first_name' => 'Sooner', 'preferred_date' => today()->addDay()]);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.appointments.index'))
            ->assertSeeInOrder(['Sooner Obi', 'Later Obi']);
    }

    public function test_admin_can_filter_by_status(): void
    {
        $this->appointment(['first_name' => 'Ada']);
        $this->appointment(['first_name' => 'Bola'])->forceFill(['status' => 'confirmed'])->save();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.appointments.index', ['status' => 'confirmed']))
            ->assertSee('Bola Obi')
            ->assertDontSee('Ada Obi');
    }

    public function test_admin_sees_the_booking_with_a_drafted_confirmation_email(): void
    {
        $appointment = $this->appointment(['notes' => 'Bring fabric samples.']);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.appointments.show', $appointment))
            ->assertOk()
            ->assertSee('Bring fabric samples.')
            ->assertSee('mailto:ada@example.com', false)
            ->assertSee('Confirm by email');
    }

    public function test_admin_can_confirm_and_add_private_notes(): void
    {
        $appointment = $this->appointment();

        $this->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.appointments.update', $appointment), ['status' => 'confirmed', 'admin_notes' => 'Confirmed by phone.'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $appointment->refresh();
        $this->assertSame('confirmed', $appointment->status);
        $this->assertSame('Confirmed by phone.', $appointment->admin_notes);
    }

    public function test_status_must_be_one_of_the_known_stages(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.appointments.update', $this->appointment()), ['status' => 'rescheduled'])
            ->assertSessionHasErrors(['status' => 'The selected status is invalid.']);
    }

    public function test_admin_can_delete_an_appointment(): void
    {
        $appointment = $this->appointment();

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.appointments.destroy', $appointment))
            ->assertRedirect(route('admin.appointments.index'));

        $this->assertModelMissing($appointment);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function appointment(array $overrides = []): Appointment
    {
        return Appointment::create([
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => 'ada@example.com',
            'service' => config('store.services')[0],
            'preferred_date' => today()->addDays(2),
            'time_slot' => '11:00 AM',
            ...$overrides,
        ])->refresh();
    }
}
