<?php

declare(strict_types=1);

namespace App\Infrastructure\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

final class ShareCreatedMail extends Mailable
{
    public function __construct(
        public readonly string $recipientName,
        public readonly string $documentName,
        public readonly string $shareUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Vous avez reçu un document : \"{$this->documentName}\"",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.share-created',
        );
    }
}
