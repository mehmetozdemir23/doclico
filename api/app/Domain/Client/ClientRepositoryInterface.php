<?php

declare(strict_types=1);

namespace App\Domain\Client;

use App\Domain\Identity\UserId;

interface ClientRepositoryInterface
{
    public function save(Client $client): Client;

    public function findById(ClientId $id): ?Client;

    /** @return Client[] */
    public function findByUserId(UserId $userId): array;

    /** @param ClientId[] $ids
     *  @return Client[] */
    public function findByIds(array $ids): array;

    public function delete(ClientId $id): void;
}
