<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Agent;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\Commission;
use App\Models\User;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
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


    public function agent()
    {
        return $this->belongsTo(Agent::class);
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

    /**
     * Process referral bonus on deposit approval.
     * Bonus is paid ONLY if:
     *  1. User was referred (referral record exists)
     *  2. Bonus hasn't been paid yet (status = pending)
     *  3. This deposit amount >= referral_min_deposit setting
     */
    protected function processReferralBonus(): void
    {
        $user = $this->user;

        // Find the referral record for this user (could be user or agent referral)
        $referral = Referral::where('referred_id', $user->id)->first();

        if (!$referral) {
            return;
        }

        // If bonus is already paid, nothing to do
        if ($referral->status === 'paid') {
            return;
        }

        // Check minimum deposit requirement from admin settings (in BDT)
        $minDeposit = (float) Setting::getValue('referral_min_deposit', 500);

        // Use converted_amount (BDT) for comparison, fallback to amount if not set
        $depositAmountBDT = $this->converted_amount ?? $this->amount;

        if ($depositAmountBDT < $minDeposit) {
            return; // Deposit too small, referral bonus not triggered
        }

        // Mark first qualifying deposit on referral record
        $referral->update([
            'first_deposit_made' => true,
            'first_deposit_amount' => $depositAmountBDT,
            'qualified_at' => now(),
        ]);

        // Pay bonus based on referrer type
        if ($referral->referrer_type === 'user' && $referral->referrer_id) {
            $this->payUserReferralBonus($referral, $user);
        } elseif ($referral->referrer_type === 'agent' && $referral->referrer_agent_id) {
            $this->payAgentReferralBonus($referral, $user);
        }
    }

    /**
     * Pay referral bonus to a Freelancer referrer (user → user referral)
     */
    protected function payUserReferralBonus(Referral $referral, User $referredUser): void
    {
        $bonusAmount = (float) Setting::getValue('referral_signup_bonus', 500);

        if ($bonusAmount <= 0) {
            return;
        }

        $referrer = User::find($referral->referrer_id);
        if (!$referrer || !$referrer->wallet) {
            return;
        }

        $referrer->wallet->addToMain($bonusAmount);

        Transaction::create([
            'user_id' => $referrer->id,
            'transaction_id' => 'REF' . strtoupper(uniqid()),
            'type' => 'referral_bonus',
            'amount' => $bonusAmount,
            'currency' => 'BDT',
            'status' => 'completed',
            'description' => 'Referral bonus – ' . $referredUser->name . ' made qualifying deposit',
            'transactionable_type' => Referral::class,
            'transactionable_id' => $referral->id,
            'balance_before' => $referrer->wallet->main_balance - $bonusAmount,
            'balance_after' => $referrer->wallet->main_balance,
        ]);

        $referral->update([
            'bonus_amount' => $bonusAmount,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $referrer->id,
            'title' => 'Referral Bonus Received!',
            'message' => 'You earned ৳' . number_format($bonusAmount, 2) . ' referral bonus! ' . $referredUser->name . ' made a qualifying deposit.',
            'type' => 'success',
            'icon' => 'gift',
        ]);
    }

    /**
     * Pay referral bonus to a Leader referrer (agent → user referral)
     */
    protected function payAgentReferralBonus(Referral $referral, User $referredUser): void
    {
        $leaderBonus = (float) Setting::getValue('leader_direct_signup_bonus', 500);

        if ($leaderBonus <= 0) {
            return;
        }

        $agent = Agent::find($referral->referrer_agent_id);
        if (!$agent) {
            return;
        }

        // Add to agent earnings
        $agent->increment('total_earnings', $leaderBonus);
        $agent->increment('pending_earnings', $leaderBonus);

        $referral->update([
            'bonus_amount' => $leaderBonus,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        Notification::create([
            'notifiable_type' => Agent::class,
            'notifiable_id' => $agent->id,
            'title' => 'Referral Bonus Earned!',
            'message' => 'You earned ৳' . number_format($leaderBonus, 2) . ' referral bonus! ' . $referredUser->name . ' made a qualifying deposit.',
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
