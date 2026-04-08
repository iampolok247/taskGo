<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'target',
        'is_active',
        'is_pinned',
        'starts_at',
        'ends_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_pinned' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function isVisible(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'info' => 'blue',
            'warning' => 'yellow',
            'promo' => 'purple',
            'update' => 'green',
            default => 'gray',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'info' => 'information-circle',
            'warning' => 'exclamation-triangle',
            'promo' => 'gift',
            'update' => 'arrow-path',
            default => 'megaphone',
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeForUsers($query)
    {
        return $query->whereIn('target', ['all', 'users']);
    }

    public function scopeForAgents($query)
    {
        return $query->whereIn('target', ['all', 'agents']);
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }
}
