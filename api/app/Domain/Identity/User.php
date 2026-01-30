<?php

declare(strict_types=1);

namespace App\Domain\Identity;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public string $firstName,
        public string $lastName,
        public Email $email,
        public string $password,
    ) {}

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
