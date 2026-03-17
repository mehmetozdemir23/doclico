<?php

declare(strict_types=1);

namespace App\Application\Client;

use App\Domain\Client\ClientId;
use App\Domain\Identity\UserId;

final readonly class UpdateClientData
{
    public function __construct(
        public ClientId $clientId,
        public UserId $currentUserId,
        public string $nom,
        public ?string $adresse,
        public ?string $email,
        public ?string $telephone,
        public ?string $siret,
    ) {}
}
