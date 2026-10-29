<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database in exact dependency order.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminSeeder::class,
            MenuSeeder::class,
            SettingSeeder::class,
            NotificationTemplateSeeder::class,
            StaffSeeder::class,
            CourseBatchSeeder::class,
            StudentSeeder::class,
            TestSeeder::class,
        ]);
    }
}
