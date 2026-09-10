<?php

namespace App\Observers;

use App\Mail\AnnouncementPostedMail;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AnnouncementObserver
{
    public function created(Announcement $announcement): void
    {
        $this->sendPublishedAnnouncementEmails($announcement);
    }

    public function updated(Announcement $announcement): void
    {
        if ($announcement->wasChanged('is_published') && $announcement->is_published) {
            $this->sendPublishedAnnouncementEmails($announcement);
        }
    }

    protected function sendPublishedAnnouncementEmails(Announcement $announcement): void
    {
        if (! $announcement->is_published) {
            return;
        }

        $students = User::query()
            ->where('role', 'student')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->get();

        foreach ($students as $student) {
            Mail::to($student->email)->queue(new AnnouncementPostedMail($announcement));
        }
    }
}
