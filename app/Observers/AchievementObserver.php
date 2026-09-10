<?php

namespace App\Observers;

use App\Mail\AchievementPostedMail;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AchievementObserver
{
    public function created(Achievement $achievement): void
    {
        if ($achievement->is_published) {
            $this->sendPublishedAchievementEmails($achievement);
        }
    }

    public function updated(Achievement $achievement): void
    {
        if ($achievement->is_published && $achievement->wasChanged('is_published')) {
            $this->sendPublishedAchievementEmails($achievement);
        }
    }

    public function sendPublishedAchievementEmails(Achievement $achievement): void
    {
        if (! $achievement->is_published) {
            return;
        }

        $query = User::query()
            ->where('role', 'student')
            ->whereNotNull('email')
            ->where('email', '!=', '');

        if ($achievement->visible_to_all) {
            $students = $query->get();
        } else {
            $selectedIds = $achievement->visibleToStudents()->pluck('users.id')->all();
            if (empty($selectedIds)) {
                return;
            }

            $students = $query->whereIn('id', $selectedIds)->get();
        }

        foreach ($students as $student) {
            Mail::to($student->email)->queue(new AchievementPostedMail($achievement));
        }
    }
}
