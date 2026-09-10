<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    protected $fillable = ['user_id', 'created_by', 'title', 'recipient_name', 'category', 'description', 'achieved_at', 'status', 'is_published', 'visible_to_all'];

    protected $casts = [
        'achieved_at' => 'datetime',
        'is_published' => 'boolean',
        'visible_to_all' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the students who can view this achievement (when not visible to all)
     */
    public function visibleToStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'achievement_visibility', 'achievement_id', 'user_id')->withTimestamps();
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'academic' => 'Academic Award',
            'board' => 'Board Passers',
            'milestone' => 'Milestone',
            default => 'Achievement',
        };
    }

    public function isVisibleTo(User $user): bool
    {
        if ($this->visible_to_all) {
            return true;
        }

        if ($this->user_id === $user->id) {
            return true;
        }

        return $this->visibleToStudents()->where('users.id', $user->id)->exists();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'awarded' => '<span class="badge bg-success">Awarded</span>',
            'revoked' => '<span class="badge bg-danger">Revoked</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'academic' => '#9333EA',
            'board' => '#D4A017',
            default => '#6B7280',
        };
    }
}
