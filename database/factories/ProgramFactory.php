<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Program>
 */
class ProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'category' => $this->faker->randomElement([
                'employment', 'entrepreneurship', 'financial_literacy', 'leadership_development',
                'mentorship', 'scholarships', 'community_development', 'emergency_assistance',
                'womens_empowerment',
            ]),
            'excerpt' => $this->faker->sentence(),
            'description' => '<p>'.$this->faker->paragraph().'</p>',
            'is_featured' => false,
            'is_published' => true,
            'order' => 0,
        ];
    }
}
