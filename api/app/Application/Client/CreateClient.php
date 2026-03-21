<?php

declare(strict_types=1);

namespace App\Application\Client;

use App\Domain\Client\Client;
use App\Domain\Client\ClientId;
use App\Domain\Client\ClientRepositoryInterface;

final readonly class CreateClient
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {}

    public function execute(CreateClientData $data): ClientResult
    {
        $client = Client::create(
            id: ClientId::generate(),
            userId: $data->userId,
            nom: $data->nom,
            adresse: $data->adresse,
            email: $data->email,
            telephone: $data->telephone,
            siret: $data->siret,
        );

        $this->clientRepository->save($client);

        return new ClientResult(
            id: $client->id->value,
            nom: $client->nom,
            adresse: $client->adresse,
            email: $client->email,
            telephone: $client->telephone,
            siret: $client->siret,
        );
    }
}
