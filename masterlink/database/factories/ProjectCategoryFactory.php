<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectCategoryFactory extends Factory
{
    public function definition(): array
    {

        $categories = [

            'تطوير المواقع',

            'المتاجر الإلكترونية',

            'التسويق الرقمي',

            'تصميم الهوية',

            'التطبيقات البرمجية',

            'الحلول التقنية',

        ];


        $name = fake()
            ->randomElement($categories);


        return [

            'name' => $name,


            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),


            'description' => fake()->paragraph(),


            'sort_order' => fake()->numberBetween(0, 10),


            'is_active' => true,

        ];
    }
}