<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class ShareReminderMail extends Mailable
{
    public function __construct(
        public readonly string $userName,
        public readonly string $documentName,
        public readonly string $shareUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Rappel : \"{$this->documentName}\" n'a pas encore été téléchargé",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.share-reminder',
        );
    }
}
