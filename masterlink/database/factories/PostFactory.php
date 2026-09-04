<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(5);

        return [

            // استخدام Admin موجود
            'admin_id' => Admin::query()
                ->inRandomOrder()
                ->value('id'),

            // استخدام Media موجود
            'media_id' => Media::query()
                ->inRandomOrder()
                ->value('id'),

            'title' => $title,

            'slug' => Str::slug($title),

            'short_description' => fake()->sentence(),

            'content' => fake()->paragraphs(5, true),

            'published_at' => fake()->optional()->dateTime(),

            'is_featured' => fake()->boolean(),

            'is_active' => true,

        ];
    }
}
