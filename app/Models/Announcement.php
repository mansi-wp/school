<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'audience',
        'target_ids',
        'channels',
        'message',
        'scheduled_at',
        'sent_at',
        'sent_by',
        'created_at',
    ];

    protected $casts = [
        'target_ids' => 'array',
        'channels' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
