<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\Client\Client;
use App\Domain\Client\ClientId;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\ClientModel;

final class ClientMapper
{
    public static function toDomain(ClientModel $model): Client
    {
        return new Client(
            id: ClientId::fromString($model->id),
            userId: UserId::fromString($model->user_id),
            nom: $model->nom,
            adresse: $model->adresse,
            email: $model->email,
            telephone: $model->telephone,
            siret: $model->siret,
        );
    }

    public static function toModel(Client $client): ClientModel
    {
        $model = ClientModel::firstOrNew(['id' => $client->id->value]);
        $model->id = $client->id->value;
        $model->user_id = $client->userId->value;
        $model->nom = $client->nom;
        $model->adresse = $client->adresse;
        $model->email = $client->email;
        $model->telephone = $client->telephone;
        $model->siret = $client->siret;

        return $model;
    }
}
