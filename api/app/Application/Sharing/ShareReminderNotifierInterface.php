<?php

declare(strict_types=1);

namespace App\Application\Sharing;

interface ShareReminderNotifierInterface
{
    public function notify(string $userEmail, string $userName, string $documentName, string $shareUrl): void;
}
