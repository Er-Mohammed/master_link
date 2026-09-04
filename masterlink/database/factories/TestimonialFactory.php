<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'media_id' => fake()->boolean(70)
                ? (
                    Media::query()->inRandomOrder()->value('id')
                    ?? Media::factory()->create()->id
                )
                : null,

            'display_name' => fake()->randomElement([
                fake()->name(),
                fake()->company(),
            ]),

            'message' => fake()->paragraphs(2, true),

            'sort_order' => fake()->numberBetween(0, 20),

            'is_active' => true,
        ];
    }
}
