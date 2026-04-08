<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'agent';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'agent_code',
        'commission_rate',
        'total_earnings',
        'pending_earnings',
        'withdrawn_earnings',
        'status',
        'address',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'commission_rate' => 'decimal:2',
            'total_earnings' => 'decimal:2',
            'pending_earnings' => 'decimal:2',
            'withdrawn_earnings' => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($agent) {
            if (empty($agent->agent_code)) {
                $agent->agent_code = static::generateAgentCode();
            }
        });
    }

    public static function generateAgentCode(): string
    {
        do {
            $code = 'AGT' . strtoupper(substr(md5(uniqid()), 0, 5));
        } while (static::where('agent_code', $code)->exists());

        return $code;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=10b981&color=fff';
    }

    public function getTotalUsersAttribute()
    {
        return $this->users()->count();
    }

    public function getActiveUsersAttribute()
    {
        return $this->users()->where('status', 'active')->count();
    }
}
