<?php

declare(strict_types=1);

namespace App\Application\Client;

use App\Domain\Identity\UserId;

final readonly class CreateClientData
{
    public function __construct(
        public UserId $userId,
        public string $nom,
        public ?string $adresse,
        public ?string $email,
        public ?string $telephone,
        public ?string $siret,
    ) {}
}
