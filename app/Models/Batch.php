<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id',
        'name',
        'teacher_id',
        'co_teacher_id',
        'start_date',
        'end_date',
        'class_days',
        'start_time',
        'end_time',
        'room_number',
        'max_capacity',
        'enrolled_count',
        'status',
    ];

    protected $casts = [
        'class_days' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'max_capacity' => 'integer',
        'enrolled_count' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }

    public function coTeacher()
    {
        return $this->belongsTo(Staff::class, 'co_teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_batch')
            ->withPivot('is_primary', 'status')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
