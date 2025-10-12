<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'body',
        'data',
        'type',
        'user_id',
        'fcm_token',
        'sent',
        'sent_at',
        'sent_count',
        'failure_count'
    ];

    protected $casts = [
        'data' => 'array',
        'sent' => 'boolean',
        'sent_at' => 'datetime',
        'sent_count' => 'integer',
        'failure_count' => 'integer'
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
