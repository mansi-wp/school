<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemMenu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'route_name' => 'admin.dashboard', 'url' => '/admin/dashboard', 'sort_order' => 1, 'is_visible' => 1, 'permission_code' => 'dashboard.view'],
            ['name' => 'Staff Management', 'icon' => 'fas fa-user-tie', 'route_name' => 'admin.staff.index', 'url' => '/admin/staff', 'sort_order' => 2, 'is_visible' => 1, 'permission_code' => 'staff.view'],
            ['name' => 'Courses', 'icon' => 'fas fa-book', 'route_name' => 'admin.courses.index', 'url' => '/admin/courses', 'sort_order' => 3, 'is_visible' => 1, 'permission_code' => 'course.view'],
            ['name' => 'Batches', 'icon' => 'fas fa-layer-group', 'route_name' => 'admin.batches.index', 'url' => '/admin/batches', 'sort_order' => 4, 'is_visible' => 1, 'permission_code' => 'batch.view'],
            ['name' => 'Students', 'icon' => 'fas fa-user-graduate', 'route_name' => 'admin.students.index', 'url' => '/admin/students', 'sort_order' => 5, 'is_visible' => 1, 'permission_code' => 'student.view'],
            ['name' => 'Fee & Finances', 'icon' => 'fas fa-credit-card', 'route_name' => 'admin.fees.index', 'url' => '/admin/fees', 'sort_order' => 6, 'is_visible' => 1, 'permission_code' => 'fee.view'],
            ['name' => 'Attendance', 'icon' => 'fas fa-calendar-check', 'route_name' => 'admin.attendance.index', 'url' => '/admin/attendance', 'sort_order' => 7, 'is_visible' => 1, 'permission_code' => 'attendance.view'],
            ['name' => 'Tests & Exams', 'icon' => 'fas fa-file-alt', 'route_name' => 'admin.tests.index', 'url' => '/admin/tests', 'sort_order' => 8, 'is_visible' => 1, 'permission_code' => 'test.view'],
            ['name' => 'Expenses', 'icon' => 'fas fa-receipt', 'route_name' => 'admin.expenses.index', 'url' => '/admin/expenses', 'sort_order' => 9, 'is_visible' => 1, 'permission_code' => 'expense.view'],
            ['name' => 'Reports & Analytics', 'icon' => 'fas fa-chart-line', 'route_name' => 'admin.reports.index', 'url' => '/admin/reports', 'sort_order' => 10, 'is_visible' => 1, 'permission_code' => 'report.view'],
            ['name' => 'Notifications', 'icon' => 'fas fa-bell', 'route_name' => 'admin.notifications.index', 'url' => '/admin/notifications', 'sort_order' => 11, 'is_visible' => 1, 'permission_code' => 'notification.view'],
            ['name' => 'Roles & Permissions', 'icon' => 'fas fa-shield-alt', 'route_name' => 'admin.roles.index', 'url' => '/admin/roles', 'sort_order' => 12, 'is_visible' => 1, 'permission_code' => 'role.view'],
            ['name' => 'Menu Builder', 'icon' => 'fas fa-bars', 'route_name' => 'admin.menus.index', 'url' => '/admin/menus', 'sort_order' => 13, 'is_visible' => 1, 'permission_code' => 'menu.view'],
            ['name' => 'Institute Settings', 'icon' => 'fas fa-cogs', 'route_name' => 'admin.settings.index', 'url' => '/admin/settings', 'sort_order' => 14, 'is_visible' => 1, 'permission_code' => 'setting.view'],
            ['name' => 'Audit Trail', 'icon' => 'fas fa-history', 'route_name' => 'admin.audit.index', 'url' => '/admin/audit', 'sort_order' => 15, 'is_visible' => 1, 'permission_code' => 'audit.view'],
        ];

        foreach ($menus as $menu) {
            SystemMenu::firstOrCreate(['name' => $menu['name']], $menu);
        }
    }
}
