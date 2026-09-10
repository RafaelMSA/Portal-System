<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Announcement $announcement;

    /**
     * Create a new notification instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
        $this->onQueue('default');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Send email for medium and high urgency announcements
        if (in_array($this->announcement->urgency, ['medium', 'high'])) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $urgencyLabel = ucfirst($this->announcement->urgency);

        return (new MailMessage)
            ->subject("[{$urgencyLabel}] {$this->announcement->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new announcement with **{$urgencyLabel}** urgency has been published:")
            ->line("**{$this->announcement->title}**")
            ->line($this->announcement->body)
            ->line("Created by: {$this->announcement->user->name}")
            ->line("Created at: {$this->announcement->created_at->format('F d, Y h:i A')}")
            ->action('View Announcement', route('announcements.show', $this->announcement->id))
            ->line('Thank you for using our portal!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'announcement_id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'body' => substr($this->announcement->body, 0, 150) . '...',
            'urgency' => $this->announcement->urgency,
            'created_by' => $this->announcement->user->name,
        ];
    }

    /**
     * Determine whether high-urgency emails should bypass the queue.
     */
    public function shouldQueue(object $notifiable): bool
    {
        // Send high urgency emails immediately, queue others
        return $this->announcement->urgency !== 'high';
    }
}
