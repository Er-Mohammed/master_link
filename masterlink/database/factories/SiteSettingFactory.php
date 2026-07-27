<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [

            'key' => fake()->unique()->word(),

            'value' => fake()->sentence(),

            'type' => fake()->randomElement([
                'text',
                'url',
                'email'
            ]),

            'group_name' => fake()->randomElement([
                'general',
                'contact',
                'social'
            ]),

        ];
    }
}