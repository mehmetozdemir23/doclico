<?php

use App\Application\Profile\UpdateLogo;
use App\Domain\Identity\Exception\UserNotFoundException;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseHas;

it('saves the logo path in the database', function (): void {
    $user = UserModel::factory()->create();

    app(UpdateLogo::class)->execute(UserId::fromString($user->id), 'logos/test.jpg');

    assertDatabaseHas('users', ['id' => $user->id, 'logo' => 'logos/test.jpg']);
});

it('returns null when there was no previous logo', function (): void {
    $user = UserModel::factory()->create(['logo' => null]);

    $oldPath = app(UpdateLogo::class)->execute(UserId::fromString($user->id), 'logos/new.jpg');

    expect($oldPath)->toBeNull();
});

it('returns the old logo path when replacing a logo', function (): void {
    $user = UserModel::factory()->create(['logo' => 'logos/old.jpg']);

    $oldPath = app(UpdateLogo::class)->execute(UserId::fromString($user->id), 'logos/new.jpg');

    expect($oldPath)->toBe('logos/old.jpg');
    assertDatabaseHas('users', ['id' => $user->id, 'logo' => 'logos/new.jpg']);
});

it('sets the logo to null when removing it', function (): void {
    $user = UserModel::factory()->create(['logo' => 'logos/existing.jpg']);

    app(UpdateLogo::class)->execute(UserId::fromString($user->id), null);

    assertDatabaseHas('users', ['id' => $user->id, 'logo' => null]);
});

it('throws when user is not found', function (): void {
    app(UpdateLogo::class)->execute(
        UserId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        'logos/test.jpg'
    );
})->throws(UserNotFoundException::class);
