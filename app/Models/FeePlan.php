<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePlan extends Model
{
    protected $fillable = [
        'student_id',
        'installment_number',
        'due_date',
        'amount',
        'paid_amount',
        'balance_amount',
        'late_fee_per_day',
        'grace_days',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'late_fee_per_day' => 'decimal:2',
        'installment_number' => 'integer',
        'grace_days' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }
}
