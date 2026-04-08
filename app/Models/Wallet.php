<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'main_balance',
        'pending_balance',
        'withdraw_balance',
        'total_deposited',
        'total_withdrawn',
        'total_earned',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'main_balance' => 'decimal:2',
            'pending_balance' => 'decimal:2',
            'withdraw_balance' => 'decimal:2',
            'total_deposited' => 'decimal:2',
            'total_withdrawn' => 'decimal:2',
            'total_earned' => 'decimal:2',
        ];
    }

    // Accessor for bonus_balance (for backward compatibility with views)
    public function getBonusBalanceAttribute(): float
    {
        return 0.00; // Or implement actual bonus logic if needed
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addToMain(float $amount): void
    {
        $this->increment('main_balance', $amount);
    }

    public function addToPending(float $amount): void
    {
        $this->increment('pending_balance', $amount);
    }

    public function movePendingToMain(float $amount): void
    {
        if ($this->pending_balance >= $amount) {
            $this->decrement('pending_balance', $amount);
            $this->increment('main_balance', $amount);
        }
    }

    public function deductFromMain(float $amount): bool
    {
        if ($this->main_balance >= $amount) {
            $this->decrement('main_balance', $amount);
            return true;
        }
        return false;
    }

    public function addDeposit(float $amount): void
    {
        $this->increment('main_balance', $amount);
        $this->increment('total_deposited', $amount);
    }

    public function processWithdrawal(float $amount): bool
    {
        if ($this->main_balance >= $amount) {
            $this->decrement('main_balance', $amount);
            $this->increment('total_withdrawn', $amount);
            return true;
        }
        return false;
    }

    public function addEarning(float $amount): void
    {
        $this->increment('total_earned', $amount);
    }

    public function getTotalBalanceAttribute(): float
    {
        return $this->main_balance + $this->pending_balance;
    }
}
