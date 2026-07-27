<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    public function definition(): array
    {

        $services = [

            'تطوير المواقع الإلكترونية',

            'تصميم المتاجر الإلكترونية',

            'التسويق الرقمي',

            'تصميم الهوية البصرية',

            'إدارة الحملات الإعلانية',

            'تصميم واجهات المستخدم UI/UX',

        ];


        $title = fake()
            ->randomElement($services);


        return [

            'title' => $title,

            'slug' => Str::slug($title) . '-' . fake()->numberBetween(1,9999),

            'short_description' =>
                fake()->sentence(),

            'full_description' =>
                fake()->paragraphs(3, true),

            'sort_order' =>
                fake()->numberBetween(0,10),

            'is_active' => true,

        ];
    }
}