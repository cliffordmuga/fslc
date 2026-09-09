<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReceived extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Inquiry: ' . ucfirst($this->lead->inquiry_type) . ' — ' . ($this->lead->name ?? $this->lead->email),
            replyTo: [
                new \Illuminate\Mail\Mailables\Address($this->lead->email, $this->lead->name ?? 'Lead'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-received',
            with: ['lead' => $this->lead],
        );
    }
}
