<?php

use App\Application\Identity\Exception\InvalidPasswordResetTokenException;
use App\Application\Identity\ResetPassword;
use App\Application\Identity\SendPasswordResetLink;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

it('sends a reset notification to an existing user', function (): void {
    Notification::fake();

    $user = UserModel::factory()->create(['email' => 'user@example.com']);

    app(SendPasswordResetLink::class)->execute('user@example.com');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('does not throw for an unknown email', function (): void {
    Notification::fake();

    app(SendPasswordResetLink::class)->execute('unknown@example.com');

    Notification::assertNothingSent();
});

it('resets the password with a valid token', function (): void {
    $user = UserModel::factory()->create(['password' => Hash::make('old-password')]);
    $token = Password::createToken($user);

    app(ResetPassword::class)->execute(
        email: $user->email,
        token: $token,
        newPassword: 'new-password',
    );

    $user->refresh();

    expect(Hash::check('new-password', $user->password))->toBeTrue();
});

it('throws for an invalid token', function (): void {
    $user = UserModel::factory()->create();

    app(ResetPassword::class)->execute(
        email: $user->email,
        token: 'invalid-token',
        newPassword: 'new-password',
    );
})->throws(InvalidPasswordResetTokenException::class);

it('throws when email does not match the token', function (): void {
    $user = UserModel::factory()->create();
    $token = Password::createToken($user);

    app(ResetPassword::class)->execute(
        email: 'wrong@example.com',
        token: $token,
        newPassword: 'new-password',
    );
})->throws(InvalidPasswordResetTokenException::class);
