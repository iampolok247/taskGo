<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_id',
        'bonus_amount',
        'currency',
        'status',
        'first_deposit_made',
        'first_deposit_amount',
        'qualified_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'bonus_amount' => 'decimal:2',
            'first_deposit_amount' => 'decimal:2',
            'first_deposit_made' => 'boolean',
            'qualified_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isQualified(): bool
    {
        return $this->status === 'qualified';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'qualified' => 'blue',
            'paid' => 'green',
            default => 'gray',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeQualified($query)
    {
        return $query->where('status', 'qualified');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
