<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'amount',
        'currency',
        'fee',
        'final_amount',
        'method',
        'account_name',
        'account_number',
        'bank_name',
        'branch_name',
        'routing_number',
        'additional_info',
        'status',
        'rejection_reason',
        'payment_proof',
        'processed_by',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fee' => 'decimal:2',
            'final_amount' => 'decimal:2',
            'processed_at' => 'datetime',
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
    public function processor()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'method', 'code');
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(Admin $admin): void
    {
        $this->update([
            'status' => 'approved',
            'processed_by' => $admin->id,
            'processed_at' => now(),
        ]);

        // Send notification
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user_id,
            'title' => 'Withdrawal Approved!',
            'message' => 'Your withdrawal request of ৳' . number_format($this->amount, 2) . ' has been approved and is being processed.',
            'type' => 'success',
            'icon' => 'check-circle',
        ]);
    }

    public function complete(Admin $admin, ?string $proofImage = null): void
    {
        $this->update([
            'status' => 'completed',
            'payment_proof' => $proofImage,
            'processed_by' => $admin->id,
            'processed_at' => now(),
        ]);

        // Create transaction
        Transaction::create([
            'user_id' => $this->user_id,
            'transaction_id' => 'WDR' . strtoupper(uniqid()),
            'type' => 'withdrawal',
            'amount' => -$this->amount,
            'currency' => $this->currency,
            'status' => 'completed',
            'description' => 'Withdrawal via ' . ucfirst($this->method),
            'transactionable_type' => Withdrawal::class,
            'transactionable_id' => $this->id,
            'balance_before' => $this->user->wallet->main_balance,
            'balance_after' => $this->user->wallet->main_balance,
        ]);

        // Send notification
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user_id,
            'title' => 'Withdrawal Completed!',
            'message' => 'Your withdrawal of ৳' . number_format($this->final_amount, 2) . ' has been sent to your ' . $this->method_label . ' account.',
            'type' => 'success',
            'icon' => 'banknotes',
        ]);
    }

    public function reject(Admin $admin, string $reason): void
    {
        // Refund the amount back to user's wallet
        $this->user->wallet->addToMain($this->amount);

        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'processed_by' => $admin->id,
            'processed_at' => now(),
        ]);

        // Send notification
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user_id,
            'title' => 'Withdrawal Rejected',
            'message' => 'Your withdrawal request of ৳' . number_format($this->amount, 2) . ' was rejected. Amount has been refunded. Reason: ' . $reason,
            'type' => 'error',
            'icon' => 'x-circle',
        ]);
    }

    public function getPaymentProofUrlAttribute()
    {
        if ($this->payment_proof) {
            return asset('storage/' . $this->payment_proof);
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
            'approved' => 'blue',
            'completed' => 'green',
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

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
