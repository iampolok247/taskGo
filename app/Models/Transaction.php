<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'type',
        'amount',
        'currency',
        'status',
        'description',
        'transactionable_type',
        'transactionable_id',
        'balance_before',
        'balance_after',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
            'metadata' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'arrow-down-circle',
            'withdrawal' => 'arrow-up-circle',
            'task_earning' => 'check-badge',
            'referral_bonus' => 'gift',
            'commission' => 'currency-dollar',
            'adjustment' => 'adjustments-horizontal',
            'fee' => 'receipt-percent',
            default => 'banknotes',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'green',
            'withdrawal' => 'red',
            'task_earning' => 'blue',
            'referral_bonus' => 'purple',
            'commission' => 'yellow',
            'adjustment' => 'gray',
            'fee' => 'orange',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'Deposit',
            'withdrawal' => 'Withdrawal',
            'task_earning' => 'Task Earning',
            'referral_bonus' => 'Referral Bonus',
            'commission' => 'Commission',
            'adjustment' => 'Adjustment',
            'fee' => 'Fee',
            default => ucfirst($this->type),
        };
    }

    public function isCredit(): bool
    {
        return $this->amount > 0;
    }

    public function isDebit(): bool
    {
        return $this->amount < 0;
    }

    public function scopeCredits($query)
    {
        return $query->where('amount', '>', 0);
    }

    public function scopeDebits($query)
    {
        return $query->where('amount', '<', 0);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
