<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectMediaFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [

            'project_id' => Project::factory(),

            'media_id' => Media::factory(),

            'sort_order' => fake()->numberBetween(0, 20),

        ];
    }
}