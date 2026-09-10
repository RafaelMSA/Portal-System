<?php

use App\Mail\AnnouncementPostedMail;
use App\Mail\AchievementPostedMail;
use App\Models\Achievement;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('queues announcement emails to all active students when published', function () {
    Mail::fake();

    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@example.com']);
    $studentA = User::factory()->create(['role' => 'student', 'email' => 'student-a@example.com']);
    $studentB = User::factory()->create(['role' => 'student', 'email' => 'student-b@example.com']);

    Announcement::create([
        'user_id' => $admin->id,
        'title' => 'Campus update',
        'body' => 'New orientation details were published.',
        'urgency' => 'high',
        'type' => 'news',
        'is_published' => true,
        'published_at' => now(),
    ]);

    Mail::assertQueued(AnnouncementPostedMail::class, 2);
    Mail::assertQueued(AnnouncementPostedMail::class, function ($mail) use ($studentA) {
        return $mail->hasTo($studentA->email);
    });
    Mail::assertQueued(AnnouncementPostedMail::class, function ($mail) use ($studentB) {
        return $mail->hasTo($studentB->email);
    });
});

it('queues achievement emails only to selected students when not visible to all', function () {
    Mail::fake();

    $staff = User::factory()->create(['role' => 'staff', 'email' => 'staff@example.com']);
    $selected = User::factory()->create(['role' => 'student', 'email' => 'selected@example.com']);
    $other = User::factory()->create(['role' => 'student', 'email' => 'other@example.com']);

    $achievement = Achievement::create([
        'user_id' => $selected->id,
        'created_by' => $staff->id,
        'recipient_name' => 'Selected Student',
        'title' => 'Academic Excellence Award',
        'category' => 'academic',
        'description' => 'Recognized for outstanding performance.',
        'status' => 'awarded',
        'is_published' => true,
        'visible_to_all' => false,
        'achieved_at' => now(),
    ]);

    $achievement->visibleToStudents()->sync([$selected->id]);
    app(\App\Observers\AchievementObserver::class)->sendPublishedAchievementEmails($achievement);

    Mail::assertQueued(AchievementPostedMail::class, 1);
    Mail::assertQueued(AchievementPostedMail::class, function ($mail) use ($selected) {
        return $mail->hasTo($selected->email);
    });
});

it('renders the exact announcement and achievement content in their email templates', function () {
    $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@example.com']);
    $student = User::factory()->create(['role' => 'student', 'email' => 'student@example.com']);

    $announcement = Announcement::create([
        'user_id' => $admin->id,
        'title' => 'Orientation update',
        'body' => 'Room B-201 is now the new check-in location.',
        'urgency' => 'high',
        'type' => 'notice',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $achievement = Achievement::create([
        'user_id' => $student->id,
        'created_by' => $admin->id,
        'recipient_name' => 'Rafael Santos',
        'title' => 'Leadership Award',
        'category' => 'academic',
        'description' => 'Awarded for outstanding leadership in the student portal project.',
        'status' => 'awarded',
        'is_published' => true,
        'visible_to_all' => true,
        'achieved_at' => now(),
    ]);

    $announcementHtml = (new AnnouncementPostedMail($announcement))->render();
    $achievementHtml = (new AchievementPostedMail($achievement))->render();

    expect($announcementHtml)->toContain('Orientation update')
        ->toContain('Room B-201 is now the new check-in location.');

    expect($achievementHtml)->toContain('Leadership Award')
        ->toContain('Awarded for outstanding leadership in the student portal project.');
});
