<?php

declare(strict_types=1);

namespace App\Application\Client;

final readonly class ClientResult
{
    public function __construct(
        public string $id,
        public string $nom,
        public ?string $adresse,
        public ?string $email,
        public ?string $telephone,
        public ?string $siret,
    ) {}
}
