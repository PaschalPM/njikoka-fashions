<?php

namespace Database\Factories;

use App\Models\WhatsappContact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WhatsappContact>
 */
class WhatsappContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->randomElement(['Sales', 'Custom designs', 'Customer care']),
            'number' => '1'.fake()->numerify('##########'),
            'position' => 0,
        ];
    }
}
