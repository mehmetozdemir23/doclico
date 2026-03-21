<?php

declare(strict_types=1);

namespace App\Infrastructure\Sharing;

use App\Application\Sharing\ShareReminderNotifierInterface;
use App\Infrastructure\Mail\ShareReminderMail;
use Illuminate\Support\Facades\Mail;

final class LaravelShareReminderNotifier implements ShareReminderNotifierInterface
{
    public function notify(string $userEmail, string $userName, string $documentName, string $shareUrl): void
    {
        Mail::to($userEmail)->send(new ShareReminderMail($userName, $documentName, $shareUrl));
    }
}
