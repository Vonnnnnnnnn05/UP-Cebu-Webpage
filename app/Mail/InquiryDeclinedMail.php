<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $organizationName;

    public function __construct(public Inquiry $inquiry)
    {
        $this->organizationName = !empty(trim($this->inquiry->affiliation ?? ''))
            ? trim($this->inquiry->affiliation)
            : trim($this->inquiry->full_name);
    }

    public function envelope(): Envelope
    {
        $subjectPrefix = 'Regarding your inquiry with UP Cebu TTBDO';
        $inquirySubject = $this->inquiry->subject ? ' - ' . $this->inquiry->subject : '';

        return new Envelope(
            subject: $subjectPrefix . $inquirySubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiries.declined',
            with: [
                'inquiry'          => $this->inquiry,
                'organizationName' => $this->organizationName,
            ],
        );
    }
}
