<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    protected $fillable = [
        'receipt_number',
        'student_id',
        'fee_plan_id',
        'amount_paid',
        'late_fee_charged',
        'late_fee_waived',
        'waiver_reason',
        'payment_method',
        'reference_number',
        'payment_date',
        'collected_by',
        'receipt_note',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'late_fee_charged' => 'decimal:2',
        'late_fee_waived' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feePlan()
    {
        return $this->belongsTo(FeePlan::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
}
