<?php

declare(strict_types=1);

namespace App\Application\Identity;

final readonly class UserResult
{
    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public string $email,
    ) {}

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
