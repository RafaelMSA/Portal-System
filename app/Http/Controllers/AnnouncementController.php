<?php

namespace App\Http\Controllers;

use App\Events\AnnouncementCreated;
use App\Events\AnnouncementUpdated;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index(): View
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            // Admins see all announcements
            $announcements = Announcement::with('user')->latest('created_at')->paginate(10);
        } elseif (in_array($user->role, ['staff', 'faculty'])) {
            // Staff/Faculty see all published + their own announcements
            $announcements = Announcement::with('user')
                ->where(function ($query) use ($user) {
                    $query->where('is_published', true)
                          ->orWhere('user_id', $user->id);
                })
                ->latest('created_at')
                ->paginate(10);
        } else {
            // Regular users see only published announcements
            $announcements = Announcement::with('user')
                ->where('is_published', true)
                ->latest('created_at')
                ->paginate(10);
        }

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create(): View
    {
        // Only admin, staff, and faculty can create announcements
        if (!in_array(auth()->user()->role, ['admin', 'staff', 'faculty'])) {
            abort(403, 'Unauthorized');
        }
        return view('announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Only admin, staff, and faculty can create announcements
        if (!in_array(auth()->user()->role, ['admin', 'staff', 'faculty'])) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'urgency' => 'required|in:low,medium,high',
            'type' => 'required|in:news,alert,event,notice',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date|after_or_equal:today',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['deadline'] = $validated['deadline'] ?? null;

        if ($validated['deadline']) {
            $validated['is_published'] = false;
            $validated['published_at'] = null;
        } else {
            $validated['is_published'] = $request->boolean('is_published', true);
            $validated['published_at'] = $validated['is_published'] ? ($validated['published_at'] ?? now()) : null;
        }

        $announcement = Announcement::create($validated);

        // Dispatch event to send notifications only when the announcement is published immediately
        if ($announcement->is_published) {
            AnnouncementCreated::dispatch($announcement);
        }

        // Redirect to role-based dashboard after creation
        $dashboardRoute = match(auth()->user()->role) {
            'admin' => 'admin',
            'staff', 'faculty' => 'staff',
            default => 'dashboard',
        };

        return redirect()->route($dashboardRoute)
                        ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement): View
    {
        $this->authorize('view', $announcement);
        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement): View
    {
        $this->authorize('update', $announcement);
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $this->authorize('update', $announcement);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'urgency' => 'required|in:low,medium,high',
            'type' => 'required|in:news,alert,event,notice',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date|after_or_equal:today',
            'deadline' => 'nullable|date|after_or_equal:today',
        ]);

        $validated['deadline'] = $validated['deadline'] ?? null;

        if ($validated['deadline']) {
            $validated['is_published'] = false;
            $validated['published_at'] = null;
        } else {
            $validated['is_published'] = $request->boolean('is_published', true);
            $validated['published_at'] = $validated['is_published'] ? ($validated['published_at'] ?? $announcement->published_at) : null;
        }

        $wasPublished = $announcement->is_published;

        $announcement->update($validated);

        // Dispatch event when the announcement is now published or updated while already published
        if ($announcement->is_published) {
            if (!$wasPublished || $announcement->wasChanged()) {
                AnnouncementUpdated::dispatch($announcement);
            }
        }

        return redirect()->route('announcements.show', $announcement)
                        ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $this->authorize('delete', $announcement);

        $announcement->delete();

        return redirect()->route('announcements.index')
                        ->with('success', 'Announcement deleted successfully.');
    }
}
