<?php

namespace App\Listeners;

use App\Events\AnnouncementCreated;
use App\Events\AnnouncementUpdated;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAnnouncementNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(AnnouncementCreated|AnnouncementUpdated $event): void
    {
        $this->sendNotifications($event->announcement);
    }

    /**
     * Send notifications to all users about the announcement.
     */
    protected function sendNotifications($announcement): void
    {
        // Get all users except the one who created the announcement
        $users = User::where('id', '!=', $announcement->user_id)->get();

        foreach ($users as $user) {
            // Create or update announcement notification record for visual alerts
            \App\Models\AnnouncementNotification::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'announcement_id' => $announcement->id,
                ],
                [
                    'is_read' => false,
                ]
            );

            // Send email notification if appropriate
            if (in_array($announcement->urgency, ['medium', 'high'])) {
                $user->notify(new AnnouncementNotification($announcement));
            }
        }
    }
}
