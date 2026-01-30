<?php

declare(strict_types=1);

namespace App\Application\Identity;

use App\Domain\Identity\UserId;

final readonly class UpdatePasswordData
{
    public function __construct(
        public UserId $userId,
        public string $currentPassword,
        public string $newPassword,
    ) {}
}
