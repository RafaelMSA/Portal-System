<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Determine whether the user can view any announcements.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can list announcements
    }

    /**
     * Determine whether the user can view the announcement.
     */
    public function view(User $user, Announcement $announcement): bool
    {
        // Admins see all announcements
        if ($user->role === 'admin') {
            return true;
        }

        // Other users can see published announcements, except the owner can also view their own unpublished announcement.
        return $announcement->is_published === true || $user->id === $announcement->user_id;
    }

    /**
     * Determine whether the user can create announcements.
     */
    public function create(User $user): bool
    {
        // Only admin and staff can create announcements
        return in_array($user->role, ['admin', 'staff', 'faculty']);
    }

    /**
     * Determine whether the user can update the announcement.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        // Admin can update any announcement
        if ($user->role === 'admin') {
            return true;
        }

        // Staff/Faculty can only update their own announcements
        if (in_array($user->role, ['staff', 'faculty'])) {
            return $user->id === $announcement->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the announcement.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        // Admin can delete any announcement
        if ($user->role === 'admin') {
            return true;
        }

        // Staff/Faculty can only delete their own announcements
        if (in_array($user->role, ['staff', 'faculty'])) {
            return $user->id === $announcement->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the announcement.
     */
    public function restore(User $user, Announcement $announcement): bool
    {
        return $this->delete($user, $announcement);
    }

    /**
     * Determine whether the user can permanently delete the announcement.
     */
    public function forceDelete(User $user, Announcement $announcement): bool
    {
        return $this->delete($user, $announcement);
    }
}
