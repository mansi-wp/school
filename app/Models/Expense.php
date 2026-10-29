<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'expense_number',
        'category',
        'amount',
        'expense_date',
        'paid_to',
        'payment_method',
        'reference_number',
        'attachment_path',
        'notes',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
