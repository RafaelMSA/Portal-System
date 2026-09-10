<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'phone', 'password', 'role', 'profile_photo_path'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'user_id');
    }

    public function createdAchievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'created_by');
    }

    public function visibleAchievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'achievement_visibility', 'user_id', 'achievement_id')->withTimestamps();
    }

    public function announcementNotifications(): HasMany
    {
        return $this->hasMany(AnnouncementNotification::class);
    }

    public function unreadAnnouncementCount(): int
    {
        return $this->announcementNotifications()
            ->where('is_read', false)
            ->count();
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        // Default avatar placeholder
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=8B1A1A&color=fff';
    }
}
