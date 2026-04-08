<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'title',
        'message',
        'type',
        'icon',
        'action_url',
        'action_text',
        'is_read',
        'read_at',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_at' => 'datetime',
            'data' => 'array',
        ];
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'info' => 'blue',
            'success' => 'green',
            'warning' => 'yellow',
            'error' => 'red',
            'announcement' => 'purple',
            default => 'gray',
        };
    }

    public function getTypeIconAttribute(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            'info' => 'information-circle',
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'x-circle',
            'announcement' => 'megaphone',
            default => 'bell',
        };
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
}
