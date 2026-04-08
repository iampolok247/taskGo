<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'currency_code',
        'profile_photo',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    public function reviewedTaskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class, 'reviewed_by');
    }

    public function reviewedDeposits()
    {
        return $this->hasMany(Deposit::class, 'reviewed_by');
    }

    public function processedWithdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'processed_by');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=ef4444&color=fff';
    }

    /**
     * Get the admin's currency
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    /**
     * Get the admin's currency object
     */
    public function getCurrencyAttribute()
    {
        return Currency::getByCode($this->currency_code ?? 'USD') ?? Currency::getDefault();
    }

    /**
     * Format amount in admin's currency
     */
    public function formatCurrency($amount): string
    {
        $currency = $this->getCurrencyAttribute();
        return $currency ? $currency->format($amount) : '$' . number_format($amount, 2);
    }
}
