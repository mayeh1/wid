<?php

namespace Database\Factories;

use App\Models\MembershipLevel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Membership>
 */
class MembershipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'membership_level_id' => MembershipLevel::factory(),
            'status' => 'active',
            'started_at' => now()->subMonth(),
            'expires_at' => now()->addYear(),
        ];
    }
}
