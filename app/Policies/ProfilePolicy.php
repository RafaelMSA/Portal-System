<?php

namespace App\Policies;

use App\Models\User;

class ProfilePolicy
{
    /**
     * Determine whether the user can view the profile.
     */
    public function view(User $authUser, User $user): bool
    {
        // Users can view their own profile or admins can view any
        return $authUser->id === $user->id || $authUser->role === 'admin';
    }

    /**
     * Determine whether the user can update the profile.
     */
    public function update(User $authUser, User $user): bool
    {
        // Users can update their own profile or admins can update any
        return $authUser->id === $user->id || $authUser->role === 'admin';
    }
}
