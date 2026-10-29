<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class StudentCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'student.view', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'student.create', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'student.edit', 'guard_name' => 'web']);

        $this->admin = User::factory()->create(['user_type' => 'admin']);
        $this->admin->givePermissionTo(['student.view', 'student.create', 'student.edit']);
    }

    public function test_admin_can_view_students_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.students.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_admit_new_student(): void
    {
        $course = Course::create([
            'name' => 'Test Course',
            'code' => 'TST-01',
            'category' => 'Engineering',
            'duration_months' => 12,
            'total_fee' => 50000,
            'admission_fee' => 5000,
            'installments_allowed' => 2,
            'status' => 'active',
        ]);

        $batch = Batch::create([
            'course_id' => $course->id,
            'name' => 'Batch Test Alpha',
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'class_days' => ['Mon', 'Wed'],
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_capacity' => 30,
            'enrolled_count' => 0,
            'status' => 'ongoing',
        ]);

        $postData = [
            'name' => 'Rohan Sharma',
            'email' => 'rohan.sharma@test.com',
            'phone' => '+919988776655',
            'gender' => 'male',
            'dob' => '2008-05-10',
            'current_class' => 'Class 11',
            'parent_name' => 'Mr. Sharma',
            'parent_phone' => '+919988776650',
            'batch_id' => $batch->id,
            'payment_plan' => 'lump_sum',
            'discount_amount' => 0,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.students.store'), $postData);
        $response->assertRedirect(route('admin.students.index'));

        $this->assertDatabaseHas('students', [
            'parent_name' => 'Mr. Sharma',
        ]);
    }
}
