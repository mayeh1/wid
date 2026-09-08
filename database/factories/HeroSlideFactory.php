<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\HeroSlide>
 */
class HeroSlideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'heading' => $this->faker->sentence(4),
            'subheading' => $this->faker->sentence(),
            'cta_label' => 'Learn More',
            'cta_url' => '/about',
            'is_published' => true,
            'order' => 0,
        ];
    }
}
