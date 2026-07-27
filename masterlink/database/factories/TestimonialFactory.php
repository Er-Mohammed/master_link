<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [

            // يستخدم صورة موجودة أو ينشئ واحدة إذا لم توجد
            'media_id' => Media::query()->inRandomOrder()->value('id')
                ?? Media::factory(),

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