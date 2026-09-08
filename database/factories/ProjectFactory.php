<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
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
            'category' => $this->faker->word(),
            'status' => 'current',
            'excerpt' => $this->faker->sentence(),
            'description' => '<p>'.$this->faker->paragraph().'</p>',
            'budget' => $this->faker->randomFloat(2, 5000, 50000),
            'raised' => 0,
            'location' => $this->faker->city(),
            'progress_percent' => 0,
            'is_featured' => false,
            'is_published' => true,
        ];
    }
}
