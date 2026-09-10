<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Announcement;
use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create dummy users for testing different roles
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.test',
            'role' => 'admin',
        ]);

        $staff = User::factory()->create([
            'name' => 'Staff Member',
            'username' => 'staff',
            'email' => 'staff@example.test',
            'role' => 'staff',
        ]);

        $student = User::factory()->create([
            'name' => 'Student User',
            'username' => 'student',
            'email' => 'student@example.test',
            'role' => 'student',
        ]);

        // TEST ONLY: Add 3 more test student accounts for testing purposes
        User::factory()->create([
            'name' => 'Test Student 2',
            'username' => 'student2',
            'email' => 'student2@example.test',
            'role' => 'student',
        ]);

        User::factory()->create([
            'name' => 'Test Student 3',
            'username' => 'student3',
            'email' => 'student3@example.test',
            'role' => 'student',
        ]);

        User::factory()->create([
            'name' => 'Test Student 4',
            'username' => 'student4',
            'email' => 'student4@example.test',
            'role' => 'student',
        ]);

        // TEST ONLY: Add 3 more test staff accounts for testing purposes
        User::factory()->create([
            'name' => 'Test Staff 2',
            'username' => 'staff2',
            'email' => 'staff2@example.test',
            'role' => 'staff',
        ]);

        User::factory()->create([
            'name' => 'Test Staff 3',
            'username' => 'staff3',
            'email' => 'staff3@example.test',
            'role' => 'staff',
        ]);

        User::factory()->create([
            'name' => 'Test Staff 4',
            'username' => 'staff4',
            'email' => 'staff4@example.test',
            'role' => 'staff',
        ]);

        // Seed announcements
        Announcement::create([
            'user_id' => $admin->id,
            'title' => 'Enrollment for 2nd Semester Now Open',
            'body' => 'All students are advised to complete online enrollment procedures no later than June 15, 2026. Please ensure all documents are submitted before the deadline.',
            'published_at' => now()->subDays(3),
        ]);

        Announcement::create([
            'user_id' => $staff->id,
            'title' => 'Campus Fiesta — Save the Date',
            'body' => 'Annual SFAC Fiesta celebration is set for July 4, 2026 at the main campus gymnasium. All students and faculty are invited to participate. More details coming soon!',
            'published_at' => now()->subDays(5),
        ]);

        Announcement::create([
            'user_id' => $admin->id,
            'title' => 'Scheduled Portal Maintenance',
            'body' => 'All portals will be temporarily offline on June 10, 2026 from 12:00 AM to 4:00 AM for updates and maintenance. Please plan accordingly.',
            'published_at' => now()->subDays(7),
        ]);

        // Seed achievements
        Achievement::create([
            'user_id' => $admin->id,
            'title' => 'SFAC named Top Performing School in the Nursing Licensure Examination, Region IV-A.',
            'category' => 'academic',
            'achieved_at' => now()->subDays(2),
        ]);

        Achievement::create([
            'user_id' => $admin->id,
            'title' => '96.4% passing rate achieved in the June 2026 Midwifery Board Examinations.',
            'category' => 'board',
            'achieved_at' => now()->subWeek(),
        ]);

        // Convenience: password for all seeded users is "password" (see UserFactory)
    }
}

