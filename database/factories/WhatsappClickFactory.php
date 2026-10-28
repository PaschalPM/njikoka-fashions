<?php

namespace Database\Factories;

use App\Models\WhatsappClick;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WhatsappClick>
 */
class WhatsappClickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contact_label' => 'Ify',
            'contact_number' => '2348135438144',
            'source' => 'chat',
            'visitor' => (string) Str::uuid(),
        ];
    }

    public function order(int $totalCents = 30000): static
    {
        return $this->state(['source' => 'order', 'cart_items' => 1, 'cart_total_cents' => $totalCents]);
    }
}
