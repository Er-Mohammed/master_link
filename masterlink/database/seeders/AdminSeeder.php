<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
   public function run(): void
{
    \App\Models\Admin::updateOrCreate(
        ['email' => 'admin@masterlink.com'],
        [
            'name' => 'Super Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'role' => \App\Models\Admin::ROLE_SUPER_ADMIN,
            'is_active' => true,
        ]
    );
}
}
