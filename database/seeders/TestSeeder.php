<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::first();
        $teacher = User::where('user_type', 'teacher')->first() ?? User::first();
        $student = Student::first();

        if ($course && $student) {
            $test = Test::firstOrCreate(
                ['name' => 'All India Minor Test #1 (Mechanics)'],
                [
                    'type' => 'Unit Test',
                    'course_id' => $course->id,
                    'subject' => 'Physics',
                    'test_date' => '2026-06-20',
                    'start_time' => '09:00:00',
                    'duration_minutes' => 90,
                    'total_marks' => 100.00,
                    'passing_marks' => 40.00,
                    'negative_marking' => true,
                    'negative_mark_value' => 1.00,
                    'status' => 'results_published',
                    'created_by' => $teacher->id,
                ]
            );

            TestResult::firstOrCreate(
                ['test_id' => $test->id, 'student_id' => $student->id],
                [
                    'marks_obtained' => 88.50,
                    'is_absent' => false,
                    'rank' => 3,
                    'percentile' => 94.20,
                    'remarks' => 'Strong conceptual grasp of Rotational Dynamics; watch calculation errors in friction.',
                ]
            );
        }
    }
}
