<?php

namespace Database\Factories;

use App\Models\ProjectCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [

            'category_id' => ProjectCategory::query()
                ->inRandomOrder()
                ->value('id'),

            'title' => $title,

            'slug' => Str::slug($title),

            'client_name' => fake()->company(),

            'short_description' => fake()->sentence(),

            'full_description' => fake()->paragraphs(4, true),

            'project_url' => fake()->optional()->url(),

            'completion_date' => fake()->optional()->date(),

            'is_featured' => fake()->boolean(),

            'sort_order' => fake()->numberBetween(0, 10),

            'is_active' => true,

        ];
    }
}
