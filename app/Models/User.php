<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'fcm_token',
        'address',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a customer
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Get the user's full formatted address
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line_1,
            $this->address_line_2,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country !== 'India' ? $this->country : null
        ]);

        return implode(', ', $parts) ?: $this->address;
    }

    /**
     * Get the user's profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo) {
            // Check if it's a local file path
            if (str_starts_with($this->profile_photo, 'images/')) {
                return asset($this->profile_photo);
            }
            
            // If it's not a local file path, return default avatar
            return $this->getDefaultAvatarUrl();
        }
        
        // Return default avatar with initials
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Get default avatar URL with initials
     */
    public function getDefaultAvatarUrl(): string
    {
        $initials = $this->initials();
        $colors = ['3b82f6', '10b981', '8b5cf6', 'ef4444', 'f59e0b', '6366f1', 'ec4899', '14b8a6'];
        $colorIndex = ord($initials[0]) % count($colors);
        $color = $colors[$colorIndex];
        
        return "data:image/svg+xml," . urlencode("<svg width='100' height='100' xmlns='http://www.w3.org/2000/svg'><rect width='100' height='100' fill='%23$color'/><text x='50' y='50' font-family='Arial' font-size='32' fill='white' text-anchor='middle' dy='0.35em'>$initials</text></svg>");
    }

    /**
     * Get motor logs for this customer
     */
    public function motorLogs()
    {
        return $this->hasMany(MotorLog::class, 'phone_number', 'phone_number');
    }

    /**
     * Get devices assigned to this customer
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }
}
