<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@apexcoaching.edu'],
            [
                'name' => 'System Super Administrator',
                'phone' => '+919876543210',
                'password' => Hash::make('Password@123'),
                'user_type' => 'admin',
                'is_active' => true,
            ]
        );

        $admin->assignRole('super-admin');
    }
}
