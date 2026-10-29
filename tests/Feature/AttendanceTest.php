<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'attendance.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'attendance.mark', 'guard_name' => 'web']);

        $this->teacher = User::factory()->create(['user_type' => 'teacher']);
        $this->teacher->givePermissionTo(['attendance.view', 'attendance.mark']);
    }

    public function test_attendance_register_screen_accessible(): void
    {
        $response = $this->actingAs($this->teacher)->get(route('admin.attendance.index'));
        $response->assertStatus(200);
    }
}
