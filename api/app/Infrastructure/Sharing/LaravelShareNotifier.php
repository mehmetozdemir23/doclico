<?php

declare(strict_types=1);

namespace App\Infrastructure\Sharing;

use App\Application\Sharing\Exception\ShareNotificationFailedException;
use App\Application\Sharing\ShareNotifierInterface;
use App\Infrastructure\Mail\ShareCreatedMail;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

final class LaravelShareNotifier implements ShareNotifierInterface
{
    public function notify(string $recipientEmail, string $recipientName, string $documentName, string $shareUrl): void
    {
        try {
            Mail::to($recipientEmail)->send(new ShareCreatedMail($recipientName, $documentName, $shareUrl));
        } catch (TransportExceptionInterface) {
            throw new ShareNotificationFailedException;
        }
    }
}
