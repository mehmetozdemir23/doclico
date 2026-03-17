<?php

declare(strict_types=1);

namespace App\Domain\Identity;

use DateTimeImmutable;

final readonly class User
{
    public function __construct(
        public UserId $id,
        public string $firstName,
        public string $lastName,
        public Email $email,
        public ?string $password,
        public ?string $googleId = null,
        public ?string $companyName = null,
        public ?string $siret = null,
        public ?string $address = null,
        public ?string $phone = null,
        public ?string $mentionsLegales = null,
        public ?string $numeroTva = null,
        public ?string $logo = null,
        public ?DateTimeImmutable $consentAcceptedAt = null,
        public ?string $consentPolicyVersion = null,
    ) {}

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }
}
