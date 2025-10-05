<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MotorLog extends Model
{
    protected $fillable = [
        'timestamp',
        'motor_status',
        'voltage',
        'current',
        'water_level',
        'run_time',
        'mode',
        'clock',
        'command',
        'phone_number',
        'is_synced'
    ];

    protected $casts = [
        'timestamp' => 'string',
        'voltage' => 'float',
        'current' => 'float',
        'water_level' => 'float',
        'run_time' => 'integer',
        'is_synced' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get formatted date from timestamp
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::createFromTimestamp((int)$this->timestamp / 1000)->format('Y-m-d H:i:s');
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('timestamp', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by phone number
     */
    public function scopeByPhone($query, $phoneNumber)
    {
        return $query->where('phone_number', $phoneNumber);
    }

    /**
     * Scope for filtering by motor status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('motor_status', $status);
    }

    /**
     * Scope for unsynced logs
     */
    public function scopeUnsynced($query)
    {
        return $query->where('is_synced', false);
    }

    /**
     * Get the customer associated with this motor log
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'phone_number', 'phone_number')
            ->where('role', 'customer');
    }

    /**
     * Get formatted timestamp
     */
    public function getFormattedTimestampAttribute()
    {
        return Carbon::createFromTimestamp((int)$this->timestamp / 1000)->format('Y-m-d H:i:s');
    }

    /**
     * Get time ago
     */
    public function getTimeAgoAttribute()
    {
        return Carbon::createFromTimestamp((int)$this->timestamp / 1000)->diffForHumans();
    }
}
