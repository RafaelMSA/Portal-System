<?php

namespace App\Policies;

use App\Models\Achievement;
use App\Models\User;

class AchievementPolicy
{
    /**
     * Determine whether the user can view any achievements.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can list achievements
    }

    /**
     * Determine whether the user can view the achievement.
     */
    public function view(User $user, Achievement $achievement): bool
    {
        // Admins see all achievements
        if ($user->role === 'admin') {
            return true;
        }

        // Staff/Faculty see their own achievements (all) + others' published ones
        if (in_array($user->role, ['staff', 'faculty'])) {
            if ($user->id === $achievement->created_by) {
                return true; // Can see own
            }
            return $achievement->is_published === true;
        }

        // Students see published achievements when visibility allows it
        if (!$achievement->is_published) {
            return false;
        }

        if ($achievement->visible_to_all) {
            return true;
        }

        return $achievement->user_id === $user->id
            || $achievement->visibleToStudents()->where('users.id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create achievements.
     */
    public function create(User $user): bool
    {
        // Only admin and staff can create achievements
        return in_array($user->role, ['admin', 'staff', 'faculty']);
    }

    /**
     * Determine whether the user can update the achievement.
     */
    public function update(User $user, Achievement $achievement): bool
    {
        // Admin can update any achievement
        if ($user->role === 'admin') {
            return true;
        }

        // Staff/Faculty can only update achievements they created
        if (in_array($user->role, ['staff', 'faculty'])) {
            return $user->id === $achievement->created_by;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the achievement.
     */
    public function delete(User $user, Achievement $achievement): bool
    {
        // Admin can delete any achievement
        if ($user->role === 'admin') {
            return true;
        }

        // Staff/Faculty can only delete achievements they created
        if (in_array($user->role, ['staff', 'faculty'])) {
            return $user->id === $achievement->created_by;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the achievement.
     */
    public function restore(User $user, Achievement $achievement): bool
    {
        return $this->delete($user, $achievement);
    }

    /**
     * Determine whereby the user can permanently delete the achievement.
     */
    public function forceDelete(User $user, Achievement $achievement): bool
    {
        return $this->delete($user, $achievement);
    }
}
