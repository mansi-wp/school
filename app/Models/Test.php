<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = [
        'name',
        'type',
        'course_id',
        'subject',
        'test_date',
        'start_time',
        'duration_minutes',
        'total_marks',
        'passing_marks',
        'negative_marking',
        'negative_mark_value',
        'syllabus',
        'question_paper_path',
        'status',
        'created_by',
    ];

    protected $casts = [
        'test_date' => 'date',
        'total_marks' => 'decimal:2',
        'passing_marks' => 'decimal:2',
        'negative_marking' => 'boolean',
        'negative_mark_value' => 'decimal:2',
        'duration_minutes' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
