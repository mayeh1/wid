<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'donor_name' => $this->faker->name(),
            'donor_email' => $this->faker->safeEmail(),
            'is_anonymous' => false,
            'amount' => $this->faker->randomFloat(2, 10, 500),
            'frequency' => 'one_time',
            'status' => 'pending',
        ];
    }
}
