<?php

declare(strict_types=1);

namespace App\Domain\Client;

use App\Domain\Identity\UserId;

final readonly class Client
{
    public function __construct(
        public ClientId $id,
        public UserId $userId,
        public string $nom,
        public ?string $adresse,
        public ?string $email,
        public ?string $telephone,
        public ?string $siret,
    ) {}

    public static function create(
        ClientId $id,
        UserId $userId,
        string $nom,
        ?string $adresse,
        ?string $email,
        ?string $telephone,
        ?string $siret,
    ): self {
        return new self(
            id: $id,
            userId: $userId,
            nom: $nom,
            adresse: $adresse,
            email: $email,
            telephone: $telephone,
            siret: $siret,
        );
    }

    public function update(
        string $nom,
        ?string $adresse,
        ?string $email,
        ?string $telephone,
        ?string $siret,
    ): self {
        return new self(
            id: $this->id,
            userId: $this->userId,
            nom: $nom,
            adresse: $adresse,
            email: $email,
            telephone: $telephone,
            siret: $siret,
        );
    }
}
