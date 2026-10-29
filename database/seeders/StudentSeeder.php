<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Batch;
use App\Models\FeePlan;
use App\Models\FeePayment;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Parent User
        $parentUser = User::firstOrCreate(
            ['email' => 'suresh.patel@parent.apex.edu'],
            [
                'name' => 'Suresh Patel (Parent)',
                'phone' => '+919876543216',
                'password' => Hash::make('Password@123'),
                'user_type' => 'parent',
                'is_active' => true,
            ]
        );
        $parentUser->assignRole('parent');

        // 2. Student User
        $studentUser = User::firstOrCreate(
            ['email' => 'aarav.patel@student.apex.edu'],
            [
                'name' => 'Aarav Patel',
                'phone' => '+919876543215',
                'password' => Hash::make('Password@123'),
                'user_type' => 'student',
                'is_active' => true,
            ]
        );
        $studentUser->assignRole('student');

        // 3. Student Record
        $student = Student::firstOrCreate(
            ['roll_number' => 'APEX-2026-001'],
            [
                'user_id' => $studentUser->id,
                'gender' => 'male',
                'dob' => '2009-08-14',
                'school_name' => 'Delhi Public School R.K. Puram',
                'current_class' => 'Class 11',
                'parent_name' => 'Suresh Patel',
                'parent_phone' => '+919876543216',
                'parent_email' => 'suresh.patel@parent.apex.edu',
                'address_line1' => 'Tower 4, Flat 702, Emerald Heights',
                'city' => 'Noida',
                'state' => 'Uttar Pradesh',
                'zip' => '201301',
                'admission_date' => '2026-04-10',
                'total_fee' => 180000.00,
                'discount_amount' => 10000.00,
                'discount_reason' => 'Merit',
                'final_fee' => 170000.00,
                'paid_fee' => 100000.00,
                'outstanding_fee' => 70000.00,
                'payment_plan' => 'installment_3',
                'status' => 'active',
            ]
        );

        // 4. Batch Enrollment
        $batch = Batch::where('name', 'JEE-2027-Alpha')->first();
        if ($batch) {
            $student->batches()->syncWithoutDetaching([
                $batch->id => [
                    'assigned_at' => now(),
                    'is_primary' => true,
                    'status' => 'active',
                ],
            ]);
        }

        // 5. Fee Plans
        $plans = [
            [
                'student_id' => $student->id,
                'installment_number' => 1,
                'due_date' => '2026-04-15',
                'amount' => 60000.00,
                'paid_amount' => 60000.00,
                'balance_amount' => 0.00,
                'status' => 'paid',
            ],
            [
                'student_id' => $student->id,
                'installment_number' => 2,
                'due_date' => '2026-08-15',
                'amount' => 55000.00,
                'paid_amount' => 40000.00,
                'balance_amount' => 15000.00,
                'status' => 'partial',
            ],
            [
                'student_id' => $student->id,
                'installment_number' => 3,
                'due_date' => '2026-12-15',
                'amount' => 55000.00,
                'paid_amount' => 0.00,
                'balance_amount' => 55000.00,
                'status' => 'pending',
            ],
        ];

        $createdPlans = [];
        foreach ($plans as $pData) {
            $createdPlans[$pData['installment_number']] = FeePlan::firstOrCreate(
                ['student_id' => $student->id, 'installment_number' => $pData['installment_number']],
                $pData
            );
        }

        // 6. Cashier / Admin User ID
        $cashier = User::where('user_type', 'accountant')->first() ?? User::first();

        // 7. Fee Payments
        FeePayment::firstOrCreate(
            ['receipt_number' => 'APEX/REC/2026/0001'],
            [
                'student_id' => $student->id,
                'fee_plan_id' => $createdPlans[1]->id ?? null,
                'amount_paid' => 60000.00,
                'late_fee_charged' => 0.00,
                'late_fee_waived' => 0.00,
                'payment_method' => 'net_banking',
                'reference_number' => 'HDFC982341209',
                'payment_date' => '2026-04-12',
                'collected_by' => $cashier->id,
                'receipt_note' => 'Full 1st installment clearance with admission kit',
            ]
        );

        FeePayment::firstOrCreate(
            ['receipt_number' => 'APEX/REC/2026/0142'],
            [
                'student_id' => $student->id,
                'fee_plan_id' => $createdPlans[2]->id ?? null,
                'amount_paid' => 40000.00,
                'late_fee_charged' => 500.00,
                'late_fee_waived' => 500.00,
                'waiver_reason' => 'Director approval',
                'payment_method' => 'upi',
                'reference_number' => 'UPI/261028/893271',
                'payment_date' => '2026-08-18',
                'collected_by' => $cashier->id,
                'receipt_note' => 'Part payment for 2nd installment; late fee waived by Director',
            ]
        );
    }
}
