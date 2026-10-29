<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'batch_id',
        'student_id',
        'attendance_date',
        'session',
        'status',
        'late_minutes',
        'remarks',
        'marked_by',
        'parent_notified',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'parent_notified' => 'boolean',
        'late_minutes' => 'integer',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
