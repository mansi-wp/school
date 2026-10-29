<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'refund_number',
        'student_id',
        'total_fee_paid',
        'refund_amount',
        'refund_method',
        'reason',
        'reason_notes',
        'approved_by',
        'refund_date',
    ];

    protected $casts = [
        'total_fee_paid' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'refund_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
