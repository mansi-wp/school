<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'roll_number',
        'gender',
        'dob',
        'school_name',
        'current_class',
        'parent_name',
        'parent_phone',
        'parent_email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'admission_date',
        'total_fee',
        'discount_amount',
        'discount_reason',
        'final_fee',
        'paid_fee',
        'outstanding_fee',
        'payment_plan',
        'photo_path',
        'id_proof_path',
        'status',
    ];

    protected $casts = [
        'total_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_fee' => 'decimal:2',
        'paid_fee' => 'decimal:2',
        'outstanding_fee' => 'decimal:2',
        'admission_date' => 'date',
        'dob' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batches()
    {
        return $this->belongsToMany(Batch::class, 'student_batch')
            ->withPivot('is_primary', 'status', 'assigned_at', 'transferred_at')
            ->withTimestamps();
    }

    public function feePlans()
    {
        return $this->hasMany(FeePlan::class)->orderBy('installment_number');
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class)->orderByDesc('payment_date');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
}
