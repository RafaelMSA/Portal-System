<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the user's profile.
     */
    public function edit(User $user): View
    {
        $currentUser = auth()->user();

        // Allow users to edit their own profile and admins to manage other profiles.
        if (auth()->id() !== $user->id && ! in_array($currentUser->role, ['admin', 'staff', 'faculty'], true)) {
            abort(403, 'Unauthorized');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $currentUser = auth()->user();

        // Allow self-service editing for all authenticated roles and admin-level management.
        if (auth()->id() !== $user->id && ! in_array($currentUser->role, ['admin', 'staff', 'faculty'], true)) {
            abort(403, 'Unauthorized');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        if (auth()->user()->role === 'admin') {
            $rules['username'] = 'required|string|max:255|unique:users,username,' . $user->id;
        }

        $validated = $request->validate($rules);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $current = auth()->user();

            // Allow profile photo updates for admins and for users managing their own photo.
            if (! (
                $current->role === 'admin' ||
                ($current->id === $user->id && in_array($current->role, ['student', 'staff', 'faculty'], true))
            )) {
                abort(403, 'Unauthorized to change profile photo');
            }

            // Delete old photo if exists
            if ($user->profile_photo_path) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }

            $photo = $request->file('profile_photo');
            $path = $photo->store('profiles', 'public');
            $validated['profile_photo_path'] = $path;
        }

        // Hash password only if provided
        if ($validated['password'] ?? null) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->back()
                        ->with('success', 'Profile updated successfully.');
    }
}
