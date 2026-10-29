<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'holiday_date',
        'description',
        'created_at',
    ];

    protected $casts = [
        'holiday_date' => 'date',
    ];
}
