<?php

use App\Application\Identity\Exception\InvalidCurrentPasswordException;
use App\Application\Identity\UpdatePassword;
use App\Application\Identity\UpdatePasswordData;
use App\Domain\Identity\Exception\UserNotFoundException;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Support\Facades\Hash;

it('updates the password in the database', function (): void {
    $user = UserModel::factory()->create(['password' => Hash::make('old-password')]);

    app(UpdatePassword::class)->execute(new UpdatePasswordData(
        userId: UserId::fromString($user->id),
        currentPassword: 'old-password',
        newPassword: 'new-password',
    ));

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue();
});

it('does not change other user fields when updating password', function (): void {
    $user = UserModel::factory()->create([
        'password' => Hash::make('old-password'),
        'first_name' => 'Jean',
        'email' => 'jean@example.com',
        'company_name' => 'Acme',
        'logo' => 'logos/test.jpg',
        'google_id' => 'google-123',
    ]);

    app(UpdatePassword::class)->execute(new UpdatePasswordData(
        userId: UserId::fromString($user->id),
        currentPassword: 'old-password',
        newPassword: 'new-password',
    ));

    $user->refresh();

    expect($user->first_name)->toBe('Jean')
        ->and($user->email)->toBe('jean@example.com')
        ->and($user->company_name)->toBe('Acme')
        ->and($user->logo)->toBe('logos/test.jpg')
        ->and($user->google_id)->toBe('google-123');
});

it('throws when user has no password (Google OAuth only account)', function (): void {
    $user = UserModel::factory()->create(['password' => null]);

    app(UpdatePassword::class)->execute(new UpdatePasswordData(
        userId: UserId::fromString($user->id),
        currentPassword: 'any-password',
        newPassword: 'new-password',
    ));
})->throws(InvalidCurrentPasswordException::class);

it('throws when current password is wrong', function (): void {
    $user = UserModel::factory()->create(['password' => Hash::make('real-password')]);

    app(UpdatePassword::class)->execute(new UpdatePasswordData(
        userId: UserId::fromString($user->id),
        currentPassword: 'wrong-password',
        newPassword: 'new-password',
    ));
})->throws(InvalidCurrentPasswordException::class);

it('throws when user not found', function (): void {
    app(UpdatePassword::class)->execute(new UpdatePasswordData(
        userId: UserId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        currentPassword: 'any',
        newPassword: 'new-password',
    ));
})->throws(UserNotFoundException::class);
