<?php

use App\Application\Profile\UpdateProfile;
use App\Application\Profile\UpdateProfileData;
use App\Domain\Identity\Exception\EmailAlreadyExistsException;
use App\Domain\Identity\Exception\UserNotFoundException;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseHas;

it('updates user profile', function (): void {
    $user = UserModel::factory()->create([
        'first_name' => 'Original',
        'last_name' => 'Name',
        'email' => 'original@example.com',
    ]);

    $data = new UpdateProfileData(
        userId: UserId::fromString($user->id),
        firstName: 'Updated',
        lastName: 'User',
        email: 'updated@example.com',
    );

    $result = app(UpdateProfile::class)->execute($data);

    expect($result->firstName)->toBe('Updated')
        ->and($result->lastName)->toBe('User')
        ->and($result->email)->toBe('updated@example.com');

    assertDatabaseHas('users', [
        'id' => $user->id,
        'first_name' => 'Updated',
        'last_name' => 'User',
        'email' => 'updated@example.com',
    ]);
});

it('allows keeping same email', function (): void {
    $user = UserModel::factory()->create(['email' => 'same@example.com']);

    $data = new UpdateProfileData(
        userId: UserId::fromString($user->id),
        firstName: 'New',
        lastName: 'Name',
        email: 'same@example.com',
    );

    $result = app(UpdateProfile::class)->execute($data);

    expect($result->email)->toBe('same@example.com');
});

it('throws exception when email taken by another user', function (): void {
    UserModel::factory()->create(['email' => 'taken@example.com']);
    $user = UserModel::factory()->create(['email' => 'myemail@example.com']);

    $data = new UpdateProfileData(
        userId: UserId::fromString($user->id),
        firstName: 'Test',
        lastName: 'User',
        email: 'taken@example.com',
    );

    app(UpdateProfile::class)->execute($data);
})->throws(EmailAlreadyExistsException::class);

it('throws exception when user not found', function (): void {
    $data = new UpdateProfileData(
        userId: UserId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        firstName: 'Test',
        lastName: 'User',
        email: 'test@example.com',
    );

    app(UpdateProfile::class)->execute($data);
})->throws(UserNotFoundException::class);

it('preserves existing logo', function (): void {
    $user = UserModel::factory()->create(['logo' => 'logos/existing.jpg']);

    $data = new UpdateProfileData(
        userId: UserId::fromString($user->id),
        firstName: 'Test',
        lastName: 'User',
        email: $user->email,
    );

    app(UpdateProfile::class)->execute($data);

    assertDatabaseHas('users', ['id' => $user->id, 'logo' => 'logos/existing.jpg']);
});
