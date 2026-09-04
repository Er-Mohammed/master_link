<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@masterlink.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'role' => Admin::ROLE_SUPER_ADMIN,
                'is_active' => true,
            ]
        );
    }
}
