<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'instructions',
        'task_url',
        'reward',
        'currency',
        'category',
        'daily_limit',
        'total_limit',
        'completed_count',
        'today_completed',
        'status',
        'difficulty',
        'estimated_time',
        'proof_type',
        'proof_instructions',
        'thumbnail',
        'priority',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'reward' => 'decimal:2',
            'expires_at' => 'datetime',
        ];
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function approvedSubmissions()
    {
        return $this->hasMany(TaskSubmission::class)->where('status', 'approved');
    }

    public function pendingSubmissions()
    {
        return $this->hasMany(TaskSubmission::class)->where('status', 'pending');
    }

    public function isAvailable(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->total_limit > 0 && $this->completed_count >= $this->total_limit) {
            return false;
        }

        if ($this->daily_limit > 0 && $this->today_completed >= $this->daily_limit) {
            return false;
        }

        return true;
    }

    public function canUserSubmit(User $user): bool
    {
        if (!$this->isAvailable()) {
            return false;
        }

        // Check if user already has a pending or approved submission today
        $todaySubmission = $this->submissions()
            ->where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        return !$todaySubmission;
    }

    public function incrementCompleted(): void
    {
        $this->increment('completed_count');
        $this->increment('today_completed');

        if ($this->total_limit > 0 && $this->completed_count >= $this->total_limit) {
            $this->update(['status' => 'completed']);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function ($q) {
                $q->where('total_limit', 0)
                    ->orWhereColumn('completed_count', '<', 'total_limit');
            })
            ->where(function ($q) {
                $q->where('daily_limit', 0)
                    ->orWhereColumn('today_completed', '<', 'daily_limit');
            });
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty) {
            'easy' => 'green',
            'medium' => 'yellow',
            'hard' => 'red',
            default => 'gray',
        };
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'social' => 'share',
            'survey' => 'clipboard-list',
            'video' => 'play-circle',
            'app' => 'device-mobile',
            'website' => 'globe',
            default => 'document',
        };
    }
}
