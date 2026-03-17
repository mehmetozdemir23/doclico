<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Application\Identity\Exception\InvalidPasswordResetTokenException;

interface PasswordResetServiceInterface
{
    public function sendResetLink(string $email): void;

    /**
     * @throws InvalidPasswordResetTokenException
     */
    public function reset(string $email, string $token, string $newPassword): void;
}
