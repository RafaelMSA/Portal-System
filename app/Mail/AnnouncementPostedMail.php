<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementPostedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Announcement $announcement)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Announcement: ' . $this->announcement->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement-posted',
            with: [
                'announcement' => $this->announcement,
                'title' => $this->announcement->title,
                'body' => $this->announcement->body,
                'type' => $this->announcement->type ?? 'news',
                'urgency' => $this->announcement->urgency ?? 'medium',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
