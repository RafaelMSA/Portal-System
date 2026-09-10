<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Observers\AchievementObserver;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AchievementController extends Controller
{
    /**
     * Display a listing of achievements.
     */
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $achievements = Achievement::with(['user', 'createdBy'])
                ->latest('achieved_at')
                ->paginate(10);
        } elseif (in_array($user->role, ['staff', 'faculty'])) {
            $achievements = Achievement::with(['user', 'createdBy'])
                ->where(function ($query) use ($user) {
                    $query->where('created_by', $user->id)
                          ->orWhere(function ($q) use ($user) {
                              $q->where('user_id', $user->id)
                                ->where('is_published', true);
                          });
                })
                ->latest('achieved_at')
                ->paginate(10);
        } else {
            $achievements = Achievement::with(['user', 'createdBy'])
                ->where(function ($query) use ($user) {
                    $query->where('visible_to_all', true)
                          ->orWhere('user_id', $user->id)
                          ->orWhereHas('visibleToStudents', function ($q) use ($user) {
                              $q->where('users.id', $user->id);
                          });
                })
                ->where('is_published', true)
                ->latest('achieved_at')
                ->paginate(10);
        }

        return view('achievements.index', compact('achievements'));
    }

    /**
     * Show the form for creating a new achievement.
     */
    public function create(): View
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff', 'faculty'])) {
            abort(403, 'Unauthorized');
        }

        $users = User::where('role', 'student')->get();
        return view('achievements.create', compact('users'));
    }

    /**
     * Store a newly created achievement in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Only admin, staff, and faculty can create achievements
        if (!in_array(auth()->user()->role, ['admin', 'staff', 'faculty'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'recipient_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'category' => 'required|in:academic,board,milestone',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:pending,awarded,revoked',
            'is_published' => 'boolean',
            'visible_to_all' => 'boolean',
            'visibility_user_ids' => 'required_if:visible_to_all,0|array|min:1',
            'visibility_user_ids.*' => 'exists:users,id',
            'achieved_at' => 'nullable|date',
        ]);

        $visibilityUserIds = $validated['visibility_user_ids'] ?? [];
        unset($validated['visibility_user_ids']);

        $validated['created_by'] = auth()->id();
        $validated['is_published'] = $request->boolean('is_published');
        $validated['visible_to_all'] = $request->boolean('visible_to_all');
        $validated['achieved_at'] = $validated['achieved_at'] ?? now();

        $achievement = Achievement::create($validated);

        if (!$achievement->visible_to_all) {
            $achievement->visibleToStudents()->sync($visibilityUserIds);
        }

        if ($achievement->is_published) {
            app(AchievementObserver::class)->sendPublishedAchievementEmails($achievement);
        }

        // Redirect to role-based dashboard after creation
        $dashboardRoute = match(auth()->user()->role) {
            'admin' => 'admin',
            'staff', 'faculty' => 'staff',
            default => 'dashboard',
        };

        return redirect()->route($dashboardRoute)
                        ->with('success', 'Achievement created successfully.');
    }

    /**
     * Display the specified achievement.
     */
    public function show(Achievement $achievement): View
    {
        $this->authorize('view', $achievement);
        return view('achievements.show', compact('achievement'));
    }

    /**
     * Show the form for editing the specified achievement.
     */
    public function edit(Achievement $achievement): View
    {
        $this->authorize('update', $achievement);

        $users = User::where('role', 'student')->get();
        return view('achievements.edit', compact('achievement', 'users'));
    }

    /**
     * Update the specified achievement in storage.
     */
    public function update(Request $request, Achievement $achievement): RedirectResponse
    {
        $this->authorize('update', $achievement);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'recipient_name' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'category' => 'required|in:academic,board,milestone',
            'description' => 'nullable|string|max:2000',
            'status' => 'required|in:pending,awarded,revoked',
            'is_published' => 'boolean',
            'visible_to_all' => 'boolean',
            'visibility_user_ids' => 'required_if:visible_to_all,0|array|min:1',
            'visibility_user_ids.*' => 'exists:users,id',
            'achieved_at' => 'nullable|date',
        ]);

        $visibilityUserIds = $validated['visibility_user_ids'] ?? [];
        unset($validated['visibility_user_ids']);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['visible_to_all'] = $request->boolean('visible_to_all');
        $validated['achieved_at'] = $validated['achieved_at'] ?? $achievement->achieved_at;

        $achievement->update($validated);

        if (!$achievement->visible_to_all) {
            $achievement->visibleToStudents()->sync($visibilityUserIds);
        } else {
            $achievement->visibleToStudents()->detach();
        }

        if ($achievement->is_published) {
            app(AchievementObserver::class)->sendPublishedAchievementEmails($achievement);
        }

        return redirect()->route('achievements.show', $achievement)
                        ->with('success', 'Achievement updated successfully.');
    }

    /**
     * Remove the specified achievement from storage.
     */
    public function destroy(Achievement $achievement): RedirectResponse
    {
        $this->authorize('delete', $achievement);

        $achievement->delete();

        return redirect()->route('achievements.index')
                        ->with('success', 'Achievement deleted successfully.');
    }
}
