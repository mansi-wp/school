<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'staff.view', 'staff.create', 'staff.edit', 'staff.delete',
            'course.view', 'course.create', 'course.edit', 'course.delete',
            'batch.view', 'batch.create', 'batch.edit', 'batch.delete', 'batch.transfer',
            'student.view', 'student.create', 'student.edit', 'student.delete',
            'fee.view', 'fee.collect', 'fee.refund', 'fee.waive', 'fee.defaulters',
            'attendance.view', 'attendance.mark', 'attendance.report', 'attendance.leave.approve',
            'test.view', 'test.create', 'test.edit', 'test.delete', 'test.marks.entry', 'test.publish',
            'expense.view', 'expense.create', 'expense.approve',
            'report.view', 'report.export',
            'notification.view', 'notification.send', 'notification.template.manage',
            'role.view', 'role.create', 'role.edit', 'role.delete',
            'menu.view', 'menu.create', 'menu.edit', 'menu.delete',
            'setting.view', 'setting.edit',
            'audit.view'
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        $teacher = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'web']);
        $teacher->givePermissionTo([
            'dashboard.view',
            'batch.view',
            'student.view',
            'attendance.view', 'attendance.mark', 'attendance.report',
            'test.view', 'test.create', 'test.marks.entry', 'test.publish'
        ]);

        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $accountant->givePermissionTo([
            'dashboard.view',
            'student.view',
            'fee.view', 'fee.collect', 'fee.refund', 'fee.waive', 'fee.defaulters',
            'expense.view', 'expense.create', 'expense.approve',
            'report.view', 'report.export'
        ]);

        $receptionist = Role::firstOrCreate(['name' => 'receptionist', 'guard_name' => 'web']);
        $receptionist->givePermissionTo([
            'dashboard.view',
            'student.view', 'student.create', 'student.edit',
            'course.view', 'batch.view',
            'attendance.view',
            'fee.view',
            'notification.view', 'notification.send'
        ]);

        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'parent', 'guard_name' => 'web']);
    }
}
