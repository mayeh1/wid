<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $name = $this->faker->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'role_title' => $this->faker->jobTitle(),
            'category' => 'board_member',
            'bio' => $this->faker->paragraph(),
            'email' => $this->faker->unique()->safeEmail(),
            'order' => 0,
            'is_active' => true,
        ];
    }
}
