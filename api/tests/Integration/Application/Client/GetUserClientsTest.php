<?php

use App\Application\Client\GetUserClients;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('returns clients belonging to the user', function (): void {
    $user = UserModel::factory()->create();
    $other = UserModel::factory()->create();

    ClientModel::factory()->for($user, 'user')->create(['nom' => 'Client A']);
    ClientModel::factory()->for($user, 'user')->create(['nom' => 'Client B']);
    ClientModel::factory()->for($other, 'user')->create(['nom' => 'Client C']);

    $getUserClients = app(GetUserClients::class);
    $results = $getUserClients->execute(UserId::fromString($user->id));

    expect($results)->toHaveCount(2)
        ->and(array_column($results, 'nom'))->toContain('Client A', 'Client B');
});

it('returns empty array when user has no clients', function (): void {
    $user = UserModel::factory()->create();

    $getUserClients = app(GetUserClients::class);
    $results = $getUserClients->execute(UserId::fromString($user->id));

    expect($results)->toBeEmpty();
});
