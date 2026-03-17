<?php

use App\Application\Identity\AuthenticateUser;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Support\Facades\Hash;

it('authenticates user with valid credentials', function (): void {
    UserModel::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('password123'),
    ]);

    $authenticateUser = app(AuthenticateUser::class);
    $user = $authenticateUser->execute('john@example.com', 'password123');

    expect($user)->not->toBeNull()
        ->and($user->email)->toBe('john@example.com');
});

it('returns null for invalid email', function (): void {
    $authenticateUser = app(AuthenticateUser::class);
    $user = $authenticateUser->execute('nonexistent@example.com', 'password123');

    expect($user)->toBeNull();
});

it('returns null for invalid password', function (): void {
    UserModel::factory()->create([
        'email' => 'john@example.com',
        'password' => Hash::make('correctpassword'),
    ]);

    $authenticateUser = app(AuthenticateUser::class);
    $user = $authenticateUser->execute('john@example.com', 'wrongpassword');

    expect($user)->toBeNull();
});

it('returns null for google-only account with no password', function (): void {
    UserModel::factory()->create([
        'email' => 'google@example.com',
        'password' => null,
        'google_id' => 'google-abc',
    ]);

    $authenticateUser = app(AuthenticateUser::class);
    $user = $authenticateUser->execute('google@example.com', 'anypassword');

    expect($user)->toBeNull();
});
