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
        return 0.00;
    }

    /**
     * Withdrawable balance = total_earned - total_withdrawn
     * Users can only withdraw from their earnings, NOT from deposited money
     */
    public function getWithdrawableBalanceAttribute(): float
    {
        $withdrawable = (float) $this->total_earned - (float) $this->total_withdrawn;
        return max(0, $withdrawable);
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

    /**
     * Process withdrawal - only from earnings (withdrawable balance)
     * Deducts from main_balance and increments total_withdrawn
     */
    public function processWithdrawal(float $amount): bool
    {
        // Check against withdrawable balance (earnings - already withdrawn)
        if ($this->withdrawable_balance >= $amount && $this->main_balance >= $amount) {
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
