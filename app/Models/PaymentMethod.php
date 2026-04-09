<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'icon',
        'instructions',
        'fields',
        'min_amount',
        'max_amount',
        'fee_fixed',
        'fee_percentage',
        'currency',
        'account_details',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'fields' => 'array',
            'account_details' => 'array',
            'min_amount' => 'decimal:2',
            'max_amount' => 'decimal:2',
            'fee_fixed' => 'decimal:2',
            'fee_percentage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function calculateFee(float $amount): float
    {
        $fee = $this->fee_fixed;
        
        if ($this->fee_percentage > 0) {
            $fee += ($amount * $this->fee_percentage) / 100;
        }

        return round($fee, 2);
    }

    public function getFinalAmount(float $amount): float
    {
        return $amount - $this->calculateFee($amount);
    }

    public function isValidAmount(float $amount): bool
    {
        if ($amount < $this->min_amount) {
            return false;
        }

        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }

        return true;
    }

    public function supportsDeposit(): bool
    {
        return in_array($this->type, ['deposit', 'both']);
    }

    public function supportsWithdrawal(): bool
    {
        return in_array($this->type, ['withdrawal', 'both']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDeposit($query)
    {
        return $query->whereIn('type', ['deposit', 'both']);
    }

    public function scopeForWithdrawal($query)
    {
        return $query->whereIn('type', ['withdrawal', 'both']);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Accessor for primary account detail from account_details
    public function getAccountNumberAttribute(): ?string
    {
        $details = $this->account_details ?? [];

        return $details['account_number']
            ?? $details['wallet_address']
            ?? $details['account_info']
            ?? $details['number']
            ?? $details['account']
            ?? null;
    }
}
