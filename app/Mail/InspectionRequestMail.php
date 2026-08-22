<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InspectionRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array<string, mixed> $details */
    public function __construct(public readonly array $details) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Selotemna inspection request');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.inspection-request');
    }

    /** @return array<int, mixed> */
    public function attachments(): array
    {
        return [];
    }
}
