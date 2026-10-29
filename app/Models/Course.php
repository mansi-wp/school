<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'category',
        'duration_months',
        'total_fee',
        'admission_fee',
        'installments_allowed',
        'status',
        'description',
    ];

    protected $casts = [
        'total_fee' => 'decimal:2',
        'admission_fee' => 'decimal:2',
        'duration_months' => 'integer',
        'installments_allowed' => 'integer',
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
