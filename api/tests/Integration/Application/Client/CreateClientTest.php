<?php

use App\Application\Client\CreateClient;
use App\Application\Client\CreateClientData;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseHas;

it('creates a client for a user', function (): void {
    $user = UserModel::factory()->create();

    $data = new CreateClientData(
        userId: UserId::fromString($user->id),
        nom: 'Acme Corp',
        adresse: '1 rue de la Paix, Paris',
        email: 'contact@acme.com',
        telephone: '0600000000',
        siret: null,
    );

    $createClient = app(CreateClient::class);
    $client = $createClient->execute($data);

    expect($client->nom)->toBe('Acme Corp')
        ->and($client->adresse)->toBe('1 rue de la Paix, Paris')
        ->and($client->email)->toBe('contact@acme.com')
        ->and($client->telephone)->toBe('0600000000');

    assertDatabaseHas('clients', [
        'nom' => 'Acme Corp',
        'user_id' => $user->id,
    ]);
});

it('creates a client with nullable optional fields', function (): void {
    $user = UserModel::factory()->create();

    $data = new CreateClientData(
        userId: UserId::fromString($user->id),
        nom: 'Simple Client',
        adresse: null,
        email: null,
        telephone: null,
        siret: null,
    );

    $createClient = app(CreateClient::class);
    $client = $createClient->execute($data);

    expect($client->nom)->toBe('Simple Client')
        ->and($client->adresse)->toBeNull()
        ->and($client->email)->toBeNull()
        ->and($client->telephone)->toBeNull()
        ->and($client->siret)->toBeNull();
});

it('creates a client with a siret', function (): void {
    $user = UserModel::factory()->create();

    $data = new CreateClientData(
        userId: UserId::fromString($user->id),
        nom: 'Corp with SIRET',
        adresse: null,
        email: null,
        telephone: null,
        siret: '12345678901234',
    );

    $createClient = app(CreateClient::class);
    $client = $createClient->execute($data);

    expect($client->siret)->toBe('12345678901234');

    assertDatabaseHas('clients', ['siret' => '12345678901234']);
});
