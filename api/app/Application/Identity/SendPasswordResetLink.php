<?php

declare(strict_types=1);

namespace App\Application\Identity;

final readonly class SendPasswordResetLink
{
    public function __construct(
        private PasswordResetServiceInterface $passwordResetService,
    ) {}

    public function execute(string $email): void
    {
        $this->passwordResetService->sendResetLink($email);
    }
}
