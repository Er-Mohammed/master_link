<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    public function definition(): array
    {
        return [

            'name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),

            'password' => Hash::make('password'),

            'role' => fake()->randomElement([
                Admin::ROLE_SUPER_ADMIN,
                Admin::ROLE_ADMIN,
                Admin::ROLE_CONTENT_MANAGER,
                Admin::ROLE_MARKETING,
            ]),

            'is_active' => true,

        ];
    }
}