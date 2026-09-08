<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
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
            'slug' => \Illuminate\Support\Str::slug($title).'-'.$this->faker->unique()->numberBetween(1000, 9999),
            'excerpt' => $this->faker->sentence(),
            'content' => '<p>'.$this->faker->paragraph().'</p>',
            'is_published' => true,
        ];
    }
}
