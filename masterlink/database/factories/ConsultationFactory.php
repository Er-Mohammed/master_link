<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultationFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [

            'name' => fake()->name(),

            'email' => fake()->optional()->safeEmail(),

            'phone' => fake()->optional()->phoneNumber(),

            'company_name' => fake()->optional()->company(),

            'service_id' => Service::factory(),

            'message' => fake()->paragraph(),

            'status' => fake()->randomElement([
                'new',
                'pending',
                'completed'
            ]),

        ];
    }
}