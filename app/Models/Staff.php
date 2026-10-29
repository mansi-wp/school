<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'user_id',
        'employee_code',
        'designation',
        'qualification',
        'subjects',
        'joining_date',
        'salary',
        'photo_path',
        'emergency_contact',
        'status',
        'notes',
    ];

    protected $casts = [
        'subjects' => 'array',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'teacher_id');
    }
}
