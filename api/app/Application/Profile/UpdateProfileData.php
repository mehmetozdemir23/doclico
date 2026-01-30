<?php

declare(strict_types=1);

namespace App\Application\Profile;

use App\Domain\Identity\UserId;

final readonly class UpdateProfileData
{
    public function __construct(
        public UserId $userId,
        public string $firstName,
        public string $lastName,
        public string $email,
    ) {}
}
