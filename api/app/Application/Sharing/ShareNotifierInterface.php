<?php

declare(strict_types=1);

namespace App\Application\Sharing;

interface ShareNotifierInterface
{
    public function notify(string $recipientEmail, string $recipientName, string $documentName, string $shareUrl): void;
}
