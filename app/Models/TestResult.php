<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = [
        'test_id',
        'student_id',
        'marks_obtained',
        'is_absent',
        'rank',
        'percentile',
        'remarks',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'percentile' => 'decimal:2',
        'is_absent' => 'boolean',
        'rank' => 'integer',
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
