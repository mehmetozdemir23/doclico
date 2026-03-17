<?php

declare(strict_types=1);

namespace App\Domain\Sharing\Exception;

use RuntimeException;

final class ShareNotificationUnavailableException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Ce document n\'a pas de client avec une adresse email.');
    }
}
