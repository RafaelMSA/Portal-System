<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
 
class DashboardController extends Controller
{
    /**
     * Display the SFAC Central Dashboard.
     */
    public function index(): \Illuminate\View\View
    {
        return view('dashboard.index', [
            'pageTitle'      => 'SFAC Central Dashboard',
            'academicYear'   => '2025–2026',
            'schools'        => $this->getSchools(),
            'announcements'  => $this->getAnnouncements(),
            'achievements'   => $this->getAchievements(),
            'accomplishments'=> $this->getAchievements(),
        ]);
    }
 
    // ─── SITE ROUTER DATA ────────────────────────────────────────────────────
 
    private function getSchools(): Collection
    {
        $items = [
            [
                'id'          => 1,
                'name'        => 'Saint Francis of Assisi College Main Site',
                'description' => 'Official institutional website, news, and updates.',
                'url'         => 'https://stfrancis.edu.ph/',
                'icon'        => 'fa-solid fa-school',
            ],
            [
                'id'          => 2,
                'name'        => 'SFAC Grading Portal',
                'description' => 'Faculty grade encoding and student academic records.',
                'url'         => 'https://stfrancisbacoor.com/sfac-bac-ongrade/pages/login/login.php',
                'icon'        => 'fa-solid fa-chart-bar',
            ],
            [
                'id'          => 3,
                'name'        => 'SFAC Learning Management System (LMS)',
                'description' => 'Online learning, course modules, and class resources.',
                'url'         => 'https://stfrancis.schoology.com/login?&school=2624793884',
                'icon'        => 'fa-solid fa-book-open',
            ],
            [
                'id'          => 4,
                'name'        => 'SFAC Bacoor Campus Portal',
                'description' => 'Bacoor campus portal, schedules, and announcements.',
                'url'         => 'https://stfrancisbacoor.com/',
                'icon'        => 'fa-solid fa-building-columns',
            ],
        ];
 
        // In production, replace with:
        // return \App\Models\SchoolPortal::orderBy('sort_order')->get();
        return collect($items)->map(fn($item) => (object) $item);
    }
 
    // ─── ANNOUNCEMENTS DATA ──────────────────────────────────────────────────
 
    private function getAnnouncements(): Collection
    {
        $items = [
            [
                'id'           => 1,
                'title'        => 'Enrollment for 2nd Semester Now Open',
                'body'         => 'All students are advised to complete online enrollment procedures no later than June 15, 2025.',
                'published_at' => '2025-06-02',
            ],
            [
                'id'           => 2,
                'title'        => 'Campus Fiesta — Save the Date',
                'body'         => 'Annual SFAC Fiesta celebration is set for July 4, 2025 at the main campus gymnasium.',
                'published_at' => '2025-05-28',
            ],
            [
                'id'           => 3,
                'title'        => 'Scheduled Portal Maintenance',
                'body'         => 'All portals will be temporarily offline on June 5, 2025 from 12:00 AM to 4:00 AM for updates.',
                'published_at' => '2025-05-20',
            ],
            [
                'id'           => 4,
                'title'        => 'Scholarship Application Period Open',
                'body'         => 'Qualified students may submit scholarship applications at the Registrar\'s Office until June 20, 2025.',
                'published_at' => '2025-05-15',
            ],
        ];
 
        // In production, replace with:
        // return \App\Models\Announcement::latest('published_at')->take(5)->get();
        return collect($items)->map(fn($item) => (object) $item);
    }
 
    // ─── ACHIEVEMENTS DATA ───────────────────────────────────────────────────
 
    private function getAchievements(): Collection
    {
        $items = [
            [
                'id'             => 1,
                'title'          => 'SFAC named Top Performing School in the Nursing Licensure Examination, Region IV-A.',
                'category'       => 'academic',
                'category_label' => 'Academic Award',
                'achieved_at'    => now()->subDays(2)->toDateString(),
            ],
            [
                'id'             => 2,
                'title'          => '96.4% passing rate achieved in the June 2025 Midwifery Board Examinations.',
                'category'       => 'board',
                'category_label' => 'Board Passers',
                'achieved_at'    => now()->subWeek()->toDateString(),
            ],
            [
                'id'             => 3,
                'title'          => 'SFAC celebrates 40 years of quality Catholic education in Cavite province.',
                'category'       => 'milestone',
                'category_label' => 'Milestone',
                'achieved_at'    => '2025-05-18',
            ],
            [
                'id'             => 4,
                'title'          => 'SFAC Engineering students win gold at the IECEP Regional Technical Skills Competition.',
                'category'       => 'academic',
                'category_label' => 'Academic Award',
                'achieved_at'    => '2025-05-10',
            ],
        ];
 
        // In production, replace with:
        // return \App\Models\Achievement::latest('achieved_at')->take(5)->get();
        return collect($items)->map(fn($item) => (object) $item);
    }
 
    // Chatbot removed — inline campus assistant was deprecated and removed from the dashboard view.
}
 