<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Announcement extends Model
{
    protected $fillable = ['user_id', 'title', 'body', 'urgency', 'type', 'is_published', 'published_at', 'deadline'];

    protected $casts = [
        'published_at' => 'datetime',
        'deadline' => 'datetime',
        'is_published' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(AnnouncementNotification::class);
    }

    public function getUrgencyBadgeAttribute(): string
    {
        return match($this->urgency) {
            'low' => '<span class="badge bg-secondary">Low</span>',
            'medium' => '<span class="badge bg-warning">Medium</span>',
            'high' => '<span class="badge bg-danger">High</span>',
            default => '<span class="badge bg-secondary">Low</span>',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type ?? 'news') {
            'news' => '#3B82F6',
            'alert' => '#EF4444',
            'event' => '#D4A017',
            'notice' => '#9CA3AF',
            default => '#3B82F6',
        };
    }

    public function getTypeNameAttribute(): string
    {
        return match($this->type ?? 'news') {
            'news' => 'News',
            'alert' => 'Alert',
            'event' => 'Event',
            'notice' => 'Notice',
            default => 'News',
        };
    }
}
