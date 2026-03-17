<?php

use App\Application\Client\DeleteClient;
use App\Domain\Client\ClientId;
use App\Domain\Client\Exception\ClientNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseMissing;

it('deletes a client', function (): void {
    $user = UserModel::factory()->create();
    $client = ClientModel::factory()->for($user, 'user')->create();

    $deleteClient = app(DeleteClient::class);
    $deleteClient->execute(
        ClientId::fromString($client->id),
        UserId::fromString($user->id),
    );

    assertDatabaseMissing('clients', ['id' => $client->id]);
});

it('throws exception when client not found', function (): void {
    $user = UserModel::factory()->create();

    $deleteClient = app(DeleteClient::class);
    $deleteClient->execute(
        ClientId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        UserId::fromString($user->id),
    );
})->throws(ClientNotFoundException::class);

it('throws exception when user is not the owner', function (): void {
    $owner = UserModel::factory()->create();
    $otherUser = UserModel::factory()->create();
    $client = ClientModel::factory()->for($owner, 'user')->create();

    $deleteClient = app(DeleteClient::class);
    $deleteClient->execute(
        ClientId::fromString($client->id),
        UserId::fromString($otherUser->id),
    );
})->throws(UnauthorizedException::class);
