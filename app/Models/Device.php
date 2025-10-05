<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Device extends Model
{
    protected $fillable = [
        'device_name',
        'sms_number',
        'user_id',
        'is_active',
        'description',
        'last_activity_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_activity_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the customer/user assigned to this device
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer assigned to this device (alias)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->where('role', 'customer');
    }

    /**
     * Get motor logs for this device
     */
    public function motorLogs(): HasMany
    {
        return $this->hasMany(MotorLog::class, 'phone_number', 'sms_number');
    }

    /**
     * Scope to filter active devices
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter inactive devices
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope to filter devices assigned to a customer
     */
    public function scopeAssigned($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope to filter unassigned devices
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Get the device status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return $this->is_active ? 'green' : 'red';
    }

    /**
     * Get the device status text
     */
    public function getStatusTextAttribute(): string
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    /**
     * Get formatted last activity time
     */
    public function getLastActivityAttribute(): string
    {
        if (!$this->last_activity_at) {
            return 'Never';
        }
        
        return $this->last_activity_at->diffForHumans();
    }

    /**
     * Get total motor logs count for this device
     */
    public function getTotalLogsCountAttribute(): int
    {
        return $this->motorLogs()->count();
    }

    /**
     * Update last activity timestamp
     */
    public function updateLastActivity(): void
    {
        $this->update(['last_activity_at' => now()]);
    }
}
