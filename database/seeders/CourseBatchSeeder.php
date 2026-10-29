<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Batch;
use App\Models\Staff;

class CourseBatchSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'name' => 'JEE Advanced Pinnacle (2-Year)',
                'code' => 'JEE-ADV-2Y',
                'category' => 'JEE (Main+Adv)',
                'duration_months' => 24,
                'total_fee' => 180000.00,
                'admission_fee' => 15000.00,
                'installments_allowed' => 4,
                'status' => 'active',
                'description' => 'Intensive 2-year classroom foundation & advanced syllabus for Class 11-12 IIT JEE aspirants.',
            ],
            [
                'name' => 'NEET Medical Ranker (1-Year)',
                'code' => 'NEET-MED-1Y',
                'category' => 'NEET Medical',
                'duration_months' => 12,
                'total_fee' => 125000.00,
                'admission_fee' => 10000.00,
                'installments_allowed' => 3,
                'status' => 'active',
                'description' => 'Complete coverage of Physics, Chemistry & Biology with NCERT line-by-line masterclasses.',
            ],
            [
                'name' => 'Foundation Olympiad Class 10',
                'code' => 'FOUND-OLYMP-10',
                'category' => 'Class 8-10',
                'duration_months' => 10,
                'total_fee' => 65000.00,
                'admission_fee' => 5000.00,
                'installments_allowed' => 2,
                'status' => 'active',
                'description' => 'Building high aptitude in Mathematics & Science for NTSE, PRMO and Board excellence.',
            ],
        ];

        foreach ($courses as $cData) {
            Course::firstOrCreate(['code' => $cData['code']], $cData);
        }

        $c1 = Course::where('code', 'JEE-ADV-2Y')->first();
        $c2 = Course::where('code', 'NEET-MED-1Y')->first();
        $c3 = Course::where('code', 'FOUND-OLYMP-10')->first();

        $stf1 = Staff::where('employee_code', 'STF-001')->first();
        $stf2 = Staff::where('employee_code', 'STF-002')->first();

        $batches = [
            [
                'course_id' => $c1->id,
                'name' => 'JEE-2027-Alpha',
                'teacher_id' => $stf1 ? $stf1->id : 1,
                'co_teacher_id' => $stf2 ? $stf2->id : null,
                'start_date' => '2026-04-15',
                'end_date' => '2027-03-31',
                'class_days' => ['Mon', 'Wed', 'Fri'],
                'start_time' => '08:30:00',
                'end_time' => '12:30:00',
                'room_number' => 'Hall A-101',
                'max_capacity' => 35,
                'enrolled_count' => 31,
                'status' => 'ongoing',
            ],
            [
                'course_id' => $c2->id,
                'name' => 'NEET-2027-Medix',
                'teacher_id' => $stf2 ? $stf2->id : 1,
                'co_teacher_id' => $stf1 ? $stf1->id : null,
                'start_date' => '2026-05-01',
                'end_date' => '2027-04-15',
                'class_days' => ['Tue', 'Thu', 'Sat'],
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
                'room_number' => 'Hall B-204',
                'max_capacity' => 30,
                'enrolled_count' => 28,
                'status' => 'ongoing',
            ],
            [
                'course_id' => $c3->id,
                'name' => 'Olympiad-X-Excel',
                'teacher_id' => $stf1 ? $stf1->id : 1,
                'co_teacher_id' => null,
                'start_date' => '2026-06-01',
                'end_date' => '2027-02-28',
                'class_days' => ['Mon', 'Wed', 'Sat'],
                'start_time' => '16:00:00',
                'end_time' => '18:30:00',
                'room_number' => 'Room C-302',
                'max_capacity' => 25,
                'enrolled_count' => 20,
                'status' => 'ongoing',
            ],
        ];

        foreach ($batches as $bData) {
            Batch::firstOrCreate(['name' => $bData['name']], $bData);
        }
    }
}
