<?php

declare(strict_types=1);

namespace App\Application\Client;

use App\Domain\Client\Client;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Shared\Exception\UnauthorizedException;

final readonly class UpdateClient
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {}

    public function execute(UpdateClientData $data): ClientResult
    {
        $client = $this->clientRepository->findById($data->clientId);

        if (! $client instanceof Client) {
            throw new ClientNotFoundException($data->clientId);
        }

        if (! $client->userId->equals($data->currentUserId)) {
            throw new UnauthorizedException('update this client');
        }

        $updated = $client->update(
            nom: $data->nom,
            adresse: $data->adresse,
            email: $data->email,
            telephone: $data->telephone,
            siret: $data->siret,
        );

        $this->clientRepository->save($updated);

        return new ClientResult(
            id: $updated->id->value,
            nom: $updated->nom,
            adresse: $updated->adresse,
            email: $updated->email,
            telephone: $updated->telephone,
            siret: $updated->siret,
        );
    }
}
