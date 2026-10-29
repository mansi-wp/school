<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $staffMembers = [
            [
                'email' => 'vikram.sharma@apexcoaching.edu',
                'name' => 'Prof. Vikram Sharma',
                'phone' => '+919876543211',
                'user_type' => 'teacher',
                'role' => 'teacher',
                'staff' => [
                    'employee_code' => 'STF-001',
                    'designation' => 'Head of Physics Department',
                    'qualification' => 'M.Sc. Physics (IIT Delhi)',
                    'subjects' => ['Physics', 'Applied Mechanics'],
                    'joining_date' => '2022-06-15',
                    'salary' => 85000.00,
                    'status' => 'active',
                ],
            ],
            [
                'email' => 'neha.kapoor@apexcoaching.edu',
                'name' => 'Dr. Neha Kapoor',
                'phone' => '+919876543212',
                'user_type' => 'teacher',
                'role' => 'teacher',
                'staff' => [
                    'employee_code' => 'STF-002',
                    'designation' => 'Senior Chemistry Faculty',
                    'qualification' => 'Ph.D. Organic Chemistry',
                    'subjects' => ['Organic Chemistry', 'Inorganic Chemistry'],
                    'joining_date' => '2023-01-10',
                    'salary' => 80000.00,
                    'status' => 'active',
                ],
            ],
            [
                'email' => 'priya.mehta@apexcoaching.edu',
                'name' => 'Priya Mehta',
                'phone' => '+919876543213',
                'user_type' => 'accountant',
                'role' => 'accountant',
                'staff' => [
                    'employee_code' => 'STF-003',
                    'designation' => 'Senior Chief Accountant',
                    'qualification' => 'B.Com, CA Inter',
                    'subjects' => [],
                    'joining_date' => '2021-08-01',
                    'salary' => 55000.00,
                    'status' => 'active',
                ],
            ],
            [
                'email' => 'rohit.verma@apexcoaching.edu',
                'name' => 'Rohit Verma',
                'phone' => '+919876543214',
                'user_type' => 'receptionist',
                'role' => 'receptionist',
                'staff' => [
                    'employee_code' => 'STF-004',
                    'designation' => 'Front Desk & Admissions Lead',
                    'qualification' => 'BBA in Hospitality',
                    'subjects' => [],
                    'joining_date' => '2023-04-05',
                    'salary' => 38000.00,
                    'status' => 'active',
                ],
            ],
        ];

        foreach ($staffMembers as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'phone' => $item['phone'],
                    'password' => Hash::make('Password@123'),
                    'user_type' => $item['user_type'],
                    'is_active' => true,
                ]
            );

            $user->assignRole($item['role']);

            Staff::firstOrCreate(
                ['employee_code' => $item['staff']['employee_code']],
                array_merge($item['staff'], ['user_id' => $user->id])
            );
        }
    }
}
