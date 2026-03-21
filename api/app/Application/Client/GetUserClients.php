<?php

declare(strict_types=1);

namespace App\Application\Client;

use App\Domain\Client\Client;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Identity\UserId;

final readonly class GetUserClients
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {}

    /** @return ClientResult[] */
    public function execute(UserId $userId): array
    {
        $clients = $this->clientRepository->findByUserId($userId);

        return array_map(
            fn (Client $client): ClientResult => new ClientResult(
                id: $client->id->value,
                nom: $client->nom,
                adresse: $client->adresse,
                email: $client->email,
                telephone: $client->telephone,
                siret: $client->siret,
            ),
            $clients,
        );
    }
}
