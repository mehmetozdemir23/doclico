<?php

use App\Application\Client\UpdateClient;
use App\Application\Client\UpdateClientData;
use App\Domain\Client\ClientId;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('updates a client', function (): void {
    $user = UserModel::factory()->create();
    $client = ClientModel::factory()->for($user, 'user')->create(['nom' => 'Old Name']);

    $data = new UpdateClientData(
        clientId: ClientId::fromString($client->id),
        currentUserId: UserId::fromString($user->id),
        nom: 'New Name',
        adresse: '2 avenue des Champs',
        email: 'new@example.com',
        telephone: '0700000000',
        siret: '98765432109876',
    );

    $updateClient = app(UpdateClient::class);
    $result = $updateClient->execute($data);

    expect($result->nom)->toBe('New Name')
        ->and($result->email)->toBe('new@example.com')
        ->and($result->siret)->toBe('98765432109876');
});

it('throws exception when client not found', function (): void {
    $user = UserModel::factory()->create();

    $data = new UpdateClientData(
        clientId: ClientId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        currentUserId: UserId::fromString($user->id),
        nom: 'Name',
        adresse: null,
        email: null,
        telephone: null,
        siret: null,
    );

    $updateClient = app(UpdateClient::class);
    $updateClient->execute($data);
})->throws(ClientNotFoundException::class);

it('throws exception when user is not the owner', function (): void {
    $owner = UserModel::factory()->create();
    $otherUser = UserModel::factory()->create();
    $client = ClientModel::factory()->for($owner, 'user')->create();

    $data = new UpdateClientData(
        clientId: ClientId::fromString($client->id),
        currentUserId: UserId::fromString($otherUser->id),
        nom: 'Hacked',
        adresse: null,
        email: null,
        telephone: null,
        siret: null,
    );

    $updateClient = app(UpdateClient::class);
    $updateClient->execute($data);
})->throws(UnauthorizedException::class);
