<?php

namespace App\Services;

use App\Models\Student;
use App\Models\FeePlan;
use App\Models\FeePayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FeeService
{
    /**
     * Automatically generate installment schedule based on course final fee and payment plan.
     */
    public function generateInstallments(Student $student, string $paymentPlan): void
    {
        $installmentsCount = match ($paymentPlan) {
            'full' => 1,
            'installment_2' => 2,
            'installment_3' => 3,
            'installment_4' => 4,
            'monthly' => 6,
            default => 3,
        };

        $amountPerInstallment = round($student->final_fee / $installmentsCount, 2);
        $baseDate = Carbon::parse($student->admission_date);

        for ($i = 1; $i <= $installmentsCount; $i++) {
            $dueDate = (clone $baseDate)->addMonths(($i - 1) * 2);

            FeePlan::create([
                'student_id' => $student->id,
                'installment_number' => $i,
                'due_date' => $dueDate->format('Y-m-d'),
                'amount' => $amountPerInstallment,
                'paid_amount' => 0.00,
                'balance_amount' => $amountPerInstallment,
                'late_fee_per_day' => 50.00,
                'grace_days' => 5,
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Process fee collection atomically with late fee and waiver support.
     */
    public function collectFee(array $data, int $collectorUserId): FeePayment
    {
        return DB::transaction(function () use ($data, $collectorUserId) {
            $student = Student::lockForUpdate()->findOrFail($data['student_id']);

            $amountPaid = (float)$data['amount_paid'];
            $lateFeeCharged = (float)($data['late_fee_charged'] ?? 0.00);
            $lateFeeWaived = (float)($data['late_fee_waived'] ?? 0.00);

            // Generate unique receipt number
            $count = FeePayment::count() + 1;
            $receiptNumber = 'APEX/REC/' . date('Y') . '/' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $payment = FeePayment::create([
                'receipt_number' => $receiptNumber,
                'student_id' => $student->id,
                'fee_plan_id' => $data['fee_plan_id'] ?? null,
                'amount_paid' => $amountPaid,
                'late_fee_charged' => $lateFeeCharged,
                'late_fee_waived' => $lateFeeWaived,
                'waiver_reason' => $data['waiver_reason'] ?? null,
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'] ?? null,
                'payment_date' => $data['payment_date'] ?? now()->format('Y-m-d'),
                'collected_by' => $collectorUserId,
                'receipt_note' => $data['receipt_note'] ?? null,
            ]);

            // Update student paid and outstanding balances
            $student->increment('paid_fee', $amountPaid);
            $student->update([
                'outstanding_fee' => max(0, $student->final_fee - $student->paid_fee),
            ]);

            // Update individual fee plan installment if specified
            if (!empty($data['fee_plan_id'])) {
                $plan = FeePlan::lockForUpdate()->find($data['fee_plan_id']);
                if ($plan) {
                    $newPaid = $plan->paid_amount + $amountPaid;
                    $newBalance = max(0, $plan->amount - $newPaid);
                    $plan->update([
                        'paid_amount' => $newPaid,
                        'balance_amount' => $newBalance,
                        'status' => $newBalance == 0 ? 'paid' : 'partial',
                    ]);
                }
            }

            return $payment;
        });
    }
}
