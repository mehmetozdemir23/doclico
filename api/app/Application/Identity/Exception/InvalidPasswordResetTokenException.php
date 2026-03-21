<?php

declare(strict_types=1);

namespace App\Application\Identity\Exception;

use RuntimeException;

final class InvalidPasswordResetTokenException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid or expired password reset token.');
    }
}
