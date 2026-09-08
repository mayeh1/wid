<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'role_title' => $this->faker->jobTitle(),
            'category' => 'board_member',
            'bio' => $this->faker->paragraph(),
            'email' => $this->faker->unique()->safeEmail(),
            'order' => 0,
            'is_active' => true,
        ];
    }
}
