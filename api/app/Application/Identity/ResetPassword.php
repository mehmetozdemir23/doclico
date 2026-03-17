<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Application\Identity\Exception\InvalidPasswordResetTokenException;

final readonly class ResetPassword
{
    public function __construct(
        private PasswordResetServiceInterface $passwordResetService,
    ) {}

    /**
     * @throws InvalidPasswordResetTokenException
     */
    public function execute(string $email, string $token, string $newPassword): void
    {
        $this->passwordResetService->reset($email, $token, $newPassword);
    }
}
