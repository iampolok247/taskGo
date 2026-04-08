<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'referral_code',
        'referred_by',
        'agent_id',
        'status',
        'profile_photo',
        'total_tasks_completed',
        'total_referrals',
        'last_login_at',
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
            'last_login_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = static::generateReferralCode();
            }
        });

        static::created(function ($user) {
            $user->wallet()->create([
                'currency' => 'BDT'
            ]);
        });
    }

    public static function generateReferralCode(): string
    {
        do {
            $code = 'TG' . strtoupper(substr(md5(uniqid()), 0, 6));
        } while (static::where('referral_code', $code)->exists());

        return $code;
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralRecords()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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

    public function getMainBalanceAttribute()
    {
        return $this->wallet?->main_balance ?? 0;
    }

    public function getPendingBalanceAttribute()
    {
        return $this->wallet?->pending_balance ?? 0;
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff';
    }
}
