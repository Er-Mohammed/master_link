<?php

namespace Database\Factories;

use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SiteSetting>
 */
class SiteSettingFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $type = fake()->randomElement([
            'text',
            'textarea',
            'email',
            'phone',
            'url',
            'image',
        ]);

        return [
            'key' => fake()->unique()->word(),

            'value' => match ($type) {
                'email' => fake()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'url' => fake()->url(),
                'image' => fake()->imageUrl(),
                'textarea' => fake()->paragraph(),
                default => fake()->sentence(),
            },

            'type' => $type,

            'group_name' => fake()->randomElement([
                'general',
                'contact',
                'social',
                'footer',
            ]),
        ];
    }
}