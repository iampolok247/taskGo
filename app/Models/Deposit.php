<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'converted_amount',
        'converted_currency',
        'method',
        'transaction_id',
        'sender_account',
        'receiver_account',
        'proof_image',
        'notes',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'converted_amount' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method', 'code');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(Admin $admin): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $wallet = $this->user->wallet;
        $wallet->addDeposit($this->amount);

        // Create transaction
        Transaction::create([
            'user_id' => $this->user_id,
            'transaction_id' => 'DEP' . strtoupper(uniqid()),
            'type' => 'deposit',
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => 'completed',
            'description' => 'Deposit via ' . ucfirst($this->method),
            'transactionable_type' => Deposit::class,
            'transactionable_id' => $this->id,
            'balance_before' => $wallet->main_balance - $this->amount,
            'balance_after' => $wallet->main_balance,
        ]);

        // Process agent commission
        $this->processAgentCommission();

        // Process referral bonus (if first deposit)
        $this->processReferralBonus();

        // Send notification
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user_id,
            'title' => 'Deposit Approved!',
            'message' => 'Your deposit of ৳' . number_format($this->amount, 2) . ' has been approved and added to your wallet.',
            'type' => 'success',
            'icon' => 'check-circle',
        ]);
    }

    public function reject(Admin $admin, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        // Send notification
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user_id,
            'title' => 'Deposit Rejected',
            'message' => 'Your deposit of ৳' . number_format($this->amount, 2) . ' was rejected. Reason: ' . $reason,
            'type' => 'error',
            'icon' => 'x-circle',
        ]);
    }

    protected function processAgentCommission(): void
    {
        $user = $this->user;
        
        if (!$user->agent_id) {
            return;
        }

        $agent = $user->agent;
        $commissionRate = $agent->commission_rate;

        if ($commissionRate <= 0) {
            return;
        }

        $commissionAmount = ($this->amount * $commissionRate) / 100;

        Commission::create([
            'agent_id' => $agent->id,
            'user_id' => $user->id,
            'deposit_id' => $this->id,
            'deposit_amount' => $this->amount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'currency' => $this->currency,
            'status' => 'approved',
        ]);

        $agent->increment('total_earnings', $commissionAmount);
        $agent->increment('pending_earnings', $commissionAmount);

        // Notify agent
        Notification::create([
            'notifiable_type' => Agent::class,
            'notifiable_id' => $agent->id,
            'title' => 'Commission Earned!',
            'message' => 'You earned ৳' . number_format($commissionAmount, 2) . ' commission from ' . $user->name . '\'s deposit.',
            'type' => 'success',
            'icon' => 'currency-dollar',
        ]);
    }

    protected function processReferralBonus(): void
    {
        $user = $this->user;

        if (!$user->referred_by) {
            return;
        }

        // Check if this is the first deposit
        $referral = Referral::where('referred_id', $user->id)->first();

        if (!$referral || $referral->first_deposit_made) {
            return;
        }

        // Get referral bonus amount from settings
        $bonusAmount = (float) Setting::getValue('referral_bonus_amount', 50);

        if ($bonusAmount <= 0) {
            return;
        }

        // Update referral record
        $referral->update([
            'first_deposit_made' => true,
            'first_deposit_amount' => $this->amount,
            'bonus_amount' => $bonusAmount,
            'status' => 'qualified',
            'qualified_at' => now(),
        ]);

        // Add bonus to referrer's wallet
        $referrer = User::find($user->referred_by);
        $referrer->wallet->addToMain($bonusAmount);
        $referrer->increment('total_referrals');

        // Create transaction for referrer
        Transaction::create([
            'user_id' => $referrer->id,
            'transaction_id' => 'REF' . strtoupper(uniqid()),
            'type' => 'referral_bonus',
            'amount' => $bonusAmount,
            'currency' => 'BDT',
            'status' => 'completed',
            'description' => 'Referral bonus from ' . $user->name,
            'transactionable_type' => Referral::class,
            'transactionable_id' => $referral->id,
            'balance_before' => $referrer->wallet->main_balance - $bonusAmount,
            'balance_after' => $referrer->wallet->main_balance,
        ]);

        // Update referral status to paid
        $referral->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Notify referrer
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $referrer->id,
            'title' => 'Referral Bonus Received!',
            'message' => 'You earned ৳' . number_format($bonusAmount, 2) . ' referral bonus! ' . $user->name . ' made their first deposit.',
            'type' => 'success',
            'icon' => 'gift',
        ]);
    }

    public function getProofImageUrlAttribute()
    {
        if ($this->proof_image) {
            return asset('storage/' . $this->proof_image);
        }
        return null;
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'usdt' => 'USDT',
            'bank' => 'Bank Transfer',
            'bkash' => 'bKash',
            'nagad' => 'Nagad',
            'rocket' => 'Rocket',
            default => ucfirst($this->method),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
