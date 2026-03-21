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
        public ?string $companyName = null,
        public ?string $siret = null,
        public ?string $address = null,
        public ?string $phone = null,
        public ?string $mentionsLegales = null,
        public ?string $numeroTva = null,
        public ?string $logo = null,
    ) {}

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
