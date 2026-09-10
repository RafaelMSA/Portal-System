<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if (!in_array($user->role, ['admin', 'staff', 'faculty'])) {
            abort(403, 'Unauthorized');
        }

        $users = User::query();

        // Admins see all users. Staff and faculty must NOT see student accounts.
        if ($user->role === 'admin') {
            // no extra filter
        } elseif (in_array($user->role, ['staff', 'faculty'])) {
            $users->where('role', '!=', 'student');
        }

        $users = $users->orderBy('name')->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $current = auth()->user();

        if ($current->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $current = auth()->user();

        if ($current->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:staff,student',
            'password' => 'required|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $validated['profile_photo_path'] = $photo->store('profiles', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
                         ->with('success', 'New user account created successfully.');
    }

    public function edit(User $user): View
    {
        $current = auth()->user();

        // Admins can edit any profile. Users can edit their own profile.
        if ($current->role === 'admin' || $current->id === $user->id) {
            return view('users.edit', compact('user'));
        }

        // Staff and faculty may edit staff/faculty accounts but not student accounts.
        if (in_array($current->role, ['staff', 'faculty']) && in_array($user->role, ['staff', 'faculty'])) {
            return view('users.edit', compact('user'));
        }

        abort(403, 'Unauthorized');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $current = auth()->user();

        $allowedRoles = null;

        if ($current->role === 'admin') {
            $allowedRoles = ['admin', 'staff', 'faculty', 'student'];
        } elseif (in_array($current->role, ['staff', 'faculty']) && in_array($user->role, ['staff', 'faculty'])) {
            // Staff/faculty can update other staff/faculty but cannot create or edit student accounts.
            $allowedRoles = ['staff', 'faculty'];
        } elseif ($current->id === $user->id) {
            // Users can update their own profile but cannot change their role here.
            $allowedRoles = null;
        } else {
            abort(403, 'Unauthorized');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        if ($allowedRoles !== null) {
            $rules['role'] = 'required|in:' . implode(',', $allowedRoles);
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $photo = $request->file('profile_photo');
            $validated['profile_photo_path'] = $photo->store('profiles', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
                        ->with('success', 'User profile saved successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $current = auth()->user();

        // Only admins may delete user accounts from this interface.
        if ($current->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Remove profile photo from storage if present
        if (!empty($user->profile_photo_path)) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'User account deleted successfully.');
    }
}
