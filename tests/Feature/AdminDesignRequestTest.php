<?php

namespace Tests\Feature;

use App\Models\DesignRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDesignRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_a_submitted_custom_design_form_appears_in_the_admin_as_new(): void
    {
        $this->post(route('custom-designs.store'), [
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => 'ada@example.com',
            'garment_type' => 'Dress / Gown',
            'fabrics' => ['Silk', 'Linen'],
            'description' => 'A floor-length gown in emerald for my wedding.',
        ])->assertSessionHasNoErrors();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.design-requests.index'))
            ->assertOk()
            ->assertSee('Ada Obi')
            ->assertSee('Silk, Linen')
            ->assertSee('A floor-length gown in emerald')
            ->assertSee('1<span class="sr-only"> new</span>', false);
    }

    public function test_customers_cannot_see_design_requests(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.design-requests.index'))
            ->assertForbidden();
    }

    public function test_admin_can_filter_requests_by_status(): void
    {
        $this->designRequest(['first_name' => 'Ada']);
        $this->designRequest(['first_name' => 'Bola'])->forceFill(['status' => 'completed'])->save();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.design-requests.index', ['status' => 'completed']))
            ->assertSee('Bola Obi')
            ->assertDontSee('Ada Obi');
    }

    public function test_admin_sees_the_full_brief(): void
    {
        $designRequest = $this->designRequest(['description' => "Emerald silk.\nFitted bodice."]);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.design-requests.show', $designRequest))
            ->assertOk()
            ->assertSee('Emerald silk.')
            ->assertSee('mailto:ada@example.com', false);
    }

    public function test_admin_can_update_the_status_and_private_notes(): void
    {
        $designRequest = $this->designRequest();

        $this->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.design-requests.update', $designRequest), ['status' => 'contacted', 'admin_notes' => 'Called, quoting $400.'])
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        $designRequest->refresh();
        $this->assertSame('contacted', $designRequest->status);
        $this->assertSame('Called, quoting $400.', $designRequest->admin_notes);
    }

    public function test_status_must_be_one_of_the_known_stages(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->patch(route('admin.design-requests.update', $this->designRequest()), ['status' => 'shipped'])
            ->assertSessionHasErrors(['status' => 'The selected status is invalid.']);
    }

    public function test_admin_can_delete_a_request(): void
    {
        $designRequest = $this->designRequest();

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.design-requests.destroy', $designRequest))
            ->assertRedirect(route('admin.design-requests.index'));

        $this->assertModelMissing($designRequest);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function designRequest(array $overrides = []): DesignRequest
    {
        return DesignRequest::create([
            'first_name' => 'Ada',
            'last_name' => 'Obi',
            'email' => 'ada@example.com',
            'garment_type' => 'Dress / Gown',
            'fabrics' => ['Silk'],
            'description' => 'A floor-length gown.',
            ...$overrides,
        ])->refresh();
    }
}
