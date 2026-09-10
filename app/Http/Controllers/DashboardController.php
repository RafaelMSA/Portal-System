<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Achievement;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display the SFAC Central Dashboard.
     */
    public function index(): View
    {
        $data = [
            'pageTitle' => 'SFAC Central Dashboard',
            'academicYear' => '2025–2026',
            'schools' => $this->getSchools(),
            'announcements' => $this->getAnnouncements(),
            'achievements' => $this->getAchievements(),
            'chatbot' => $this->getChatbot(),
            'unreadAnnouncementCount' => 0,
        ];

        if (auth()->check()) {
            $role = strtolower(auth()->user()->role ?? 'student');
            $data['unreadAnnouncementCount'] = auth()->user()->unreadAnnouncementCount();
            
            return match ($role) {
                'admin' => view('roles.admin', $data),
                'staff', 'faculty' => view('roles.staff', $data),
                default => view('dashboard', $data),
            };
        }

        return view('dashboard', $data);
    }

    // Student side is served from resources/views/dashboard.blade.php.
    // If you want to change the student experience, edit that Blade file.

    // --- SITE ROUTER DATA ----------------------------------------------------

    private function getSchools(): Collection
    {
        // Change these portal URLs here to update the Quick Access grid links.
        // This defines the student dashboard site router entries.
        $items = [
            [
                'name' => 'Saint Francis of Assisi College Main Site',
                'description' => 'Official institutional website, news, and updates.',
                'url' => 'https://stfrancis.edu.ph/',
                'icon' => 'fa-solid fa-school',
            ],
            [
                'name' => 'SFAC Deep Grading Portal',
                'description' => 'Faculty grade encoding and student academic records.',
                'url' => 'https://stfrancisbacoor.com/sfac-bac-ongrade/pages/login/login.php',
                'icon' => 'fa-solid fa-chart-bar',
            ],
            [
                'name' => 'SFAC Learning Management System (LMS)',
                'description' => 'Online learning, course modules, and class resources.',
                'url' => 'https://stfrancis.schoology.com/login?&school=2624793884',
                'icon' => 'fa-solid fa-book-open',
            ],
            [
                'name' => 'SFAC Bacoor Campus Portal',
                'description' => 'Bacoor campus portal, schedules, and announcements.',
                'url' => 'https://stfrancisbacoor.com/',
                'icon' => 'fa-solid fa-building-columns',
            ],
        ];

        // Production: return \App\Models\SchoolPortal::orderBy('sort_order')->get();
        return collect($items)->map(fn ($item) => (object) $item);
    }

    // --- ANNOUNCEMENTS DATA --------------------------------------------------

    private function getAnnouncements(): Collection
    {
        // Fetch from database, limit to 5 most recent published announcements
        return Announcement::with('user')
                            ->where('is_published', true)
                            ->where('published_at', '<=', now())
                            ->latest('published_at')
                            ->take(5)
                            ->get();
    }

    // --- ACHIEVEMENTS DATA ---------------------------------------------------

    private function getAchievements(): Collection
    {
        // Fetch from database, limit to 5 most recent awarded achievements
        return Achievement::with(['user', 'createdBy'])
                          ->where('status', 'awarded')
                          ->latest('achieved_at')
                          ->take(5)
                          ->get();
    }

    private function getChatbot(): object
    {
        return (object) [
            'name' => 'FrancisAI',
            'label' => 'SFAC Campus AI Assistant',
            'model' => 'claude-sonnet-4-20250514',
            'max_tokens' => 512,
            'welcome_message' => 'Hello! I\'m FrancisAI, your SFAC campus assistant. I can help with enrollment schedules, academic deadlines, campus policies, and more.',
            'system_prompt' => 'You are FrancisAI, the campus assistant for Saint Francis of Assisi College (SFAC). Be concise, polite, and helpful. Answer only questions related to SFAC campus life. Direct students to the Registrar for matters you cannot answer.',
        ];
    }
}