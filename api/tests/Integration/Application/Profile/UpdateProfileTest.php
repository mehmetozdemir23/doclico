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

    $updateProfile = app(UpdateProfile::class);
    $updatedUser = $updateProfile->execute($data);

    expect($updatedUser->firstName)->toBe('Updated')
        ->and($updatedUser->lastName)->toBe('User')
        ->and($updatedUser->email)->toBe('updated@example.com');

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

    $updateProfile = app(UpdateProfile::class);
    $updatedUser = $updateProfile->execute($data);

    expect($updatedUser->email)->toBe('same@example.com');
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

    $updateProfile = app(UpdateProfile::class);
    $updateProfile->execute($data);
})->throws(EmailAlreadyExistsException::class);

it('throws exception when user not found', function (): void {
    $data = new UpdateProfileData(
        userId: UserId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        firstName: 'Test',
        lastName: 'User',
        email: 'test@example.com',
    );

    $updateProfile = app(UpdateProfile::class);
    $updateProfile->execute($data);
})->throws(UserNotFoundException::class);
