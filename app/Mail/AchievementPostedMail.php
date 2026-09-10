<?php

namespace App\Mail;

use App\Models\Achievement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AchievementPostedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Achievement $achievement)
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Achievement Published: ' . $this->achievement->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.achievement-posted',
            with: [
                'achievement' => $this->achievement,
                'title' => $this->achievement->title,
                'description' => $this->achievement->description,
                'category' => $this->achievement->category,
                'recipientName' => $this->achievement->recipient_name ?? $this->achievement->user?->name,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
