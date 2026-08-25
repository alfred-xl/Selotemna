<?php

namespace App\Mail;

use App\Models\ContactEnquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly ContactEnquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Selotemna enquiry '.$this->enquiry->reference);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-enquiry');
    }

    /** @return array<int, mixed> */
    public function attachments(): array
    {
        return [];
    }
}
