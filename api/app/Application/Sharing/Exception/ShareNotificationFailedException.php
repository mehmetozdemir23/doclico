<?php

declare(strict_types=1);

namespace App\Application\Sharing\Exception;

use RuntimeException;

final class ShareNotificationFailedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct("L'email n'a pas pu être envoyé. Vérifiez votre configuration mail.");
    }
}
