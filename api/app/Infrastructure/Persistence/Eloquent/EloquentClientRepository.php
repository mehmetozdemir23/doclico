<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Client\Client;
use App\Domain\Client\ClientId;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Mapper\ClientMapper;

final class EloquentClientRepository implements ClientRepositoryInterface
{
    public function save(Client $client): Client
    {
        $model = ClientMapper::toModel($client);
        $model->save();

        return ClientMapper::toDomain($model);
    }

    public function findById(ClientId $id): ?Client
    {
        $model = ClientModel::find($id->value);

        return $model ? ClientMapper::toDomain($model) : null;
    }

    public function findByUserId(UserId $userId): array
    {
        return ClientModel::where('user_id', $userId->value)
            ->orderBy('nom')
            ->get()
            ->map(fn (ClientModel $model): Client => ClientMapper::toDomain($model))
            ->all();
    }

    public function findByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        return ClientModel::whereIn('id', array_map(fn (ClientId $id): string => $id->value, $ids))
            ->get()
            ->map(fn (ClientModel $model): Client => ClientMapper::toDomain($model))
            ->all();
    }

    public function delete(ClientId $id): void
    {
        ClientModel::destroy($id->value);
    }
}
