<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientLogoFactory extends Factory
{
    public function definition(): array
    {
        return [

            'media_id' => Media::query()
                ->inRandomOrder()
                ->value('id'),

            'company_name' => fake()->company(),

            'website_url' => fake()->optional()->url(),

            'sort_order' => fake()->numberBetween(0, 20),

            'is_active' => true,

        ];
    }
}
