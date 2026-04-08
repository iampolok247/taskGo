<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task_id',
        'proof_text',
        'proof_url',
        'proof_image',
        'status',
        'rejection_reason',
        'reward_amount',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reward_amount' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
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

        // Move pending earnings to main balance
        $wallet = $this->user->wallet;
        $wallet->movePendingToMain($this->reward_amount);
        $wallet->addEarning($this->reward_amount);

        // Increment task completed count
        $this->task->incrementCompleted();

        // Update user's total tasks completed
        $this->user->increment('total_tasks_completed');

        // Create transaction record
        Transaction::create([
            'user_id' => $this->user_id,
            'transaction_id' => 'TXN' . strtoupper(uniqid()),
            'type' => 'task_earning',
            'amount' => $this->reward_amount,
            'currency' => $this->task->currency,
            'status' => 'completed',
            'description' => 'Task reward: ' . $this->task->title,
            'transactionable_type' => TaskSubmission::class,
            'transactionable_id' => $this->id,
            'balance_before' => $wallet->main_balance - $this->reward_amount,
            'balance_after' => $wallet->main_balance,
        ]);

        // Send notification
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user_id,
            'title' => 'Task Approved!',
            'message' => 'Your submission for "' . $this->task->title . '" has been approved. ৳' . number_format($this->reward_amount, 2) . ' added to your wallet.',
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

        // Remove from pending balance
        $this->user->wallet->decrement('pending_balance', $this->reward_amount);

        // Send notification
        Notification::create([
            'notifiable_type' => User::class,
            'notifiable_id' => $this->user_id,
            'title' => 'Task Rejected',
            'message' => 'Your submission for "' . $this->task->title . '" was rejected. Reason: ' . $reason,
            'type' => 'error',
            'icon' => 'x-circle',
        ]);
    }

    public function getProofImageUrlAttribute()
    {
        if ($this->proof_image) {
            return asset('storage/' . $this->proof_image);
        }
        return null;
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

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
