<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PolicyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $month,
        public string $pdfContent,
        public string $filename,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Qafila Insurance — Policy Report for {$this->month}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.policy-report',
            with: ['month' => $this->month],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, $this->filename)
                ->withMime('application/pdf'),
        ];
    }
}
