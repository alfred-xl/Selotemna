<?php

namespace App\Mail;

use App\Models\ProjectEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly ProjectEnquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Omu Creek plot enquiry '.$this->enquiry->reference);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.project-enquiry');
    }

    public function attachments(): array
    {
        return [];
    }
}
