<?php

declare(strict_types=1);

namespace App\Application\Client;

use App\Domain\Client\Client;
use App\Domain\Client\ClientId;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;

final readonly class DeleteClient
{
    public function __construct(
        private ClientRepositoryInterface $clientRepository,
    ) {}

    public function execute(ClientId $clientId, UserId $currentUserId): void
    {
        $client = $this->clientRepository->findById($clientId);

        if (! $client instanceof Client) {
            throw new ClientNotFoundException($clientId);
        }

        if (! $client->userId->equals($currentUserId)) {
            throw new UnauthorizedException('delete this client');
        }

        $this->clientRepository->delete($clientId);
    }
}
