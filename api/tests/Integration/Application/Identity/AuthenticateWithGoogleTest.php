<?php

use App\Application\Identity\AuthenticateWithGoogle;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('creates a new user from google data', function (): void {
    $useCase = app(AuthenticateWithGoogle::class);

    $result = $useCase->execute(
        googleId: 'google-123',
        email: 'jean@example.com',
        firstName: 'Jean',
        lastName: 'Dupont',
    );

    expect($result)->not->toBeNull()
        ->and($result->email)->toBe('jean@example.com')
        ->and($result->firstName)->toBe('Jean')
        ->and($result->lastName)->toBe('Dupont');

    expect(UserModel::where('google_id', 'google-123')->exists())->toBeTrue();
});

it('new google user has consent fields set', function (): void {
    $useCase = app(AuthenticateWithGoogle::class);

    $useCase->execute(
        googleId: 'google-456',
        email: 'marie@example.com',
        firstName: 'Marie',
        lastName: 'Martin',
    );

    $model = UserModel::where('google_id', 'google-456')->first();

    expect($model->consent_accepted_at)->not->toBeNull()
        ->and($model->consent_policy_version)->not->toBeNull();
});

it('new google user has no password', function (): void {
    $useCase = app(AuthenticateWithGoogle::class);

    $useCase->execute(
        googleId: 'google-789',
        email: 'pierre@example.com',
        firstName: 'Pierre',
        lastName: 'Durand',
    );

    $model = UserModel::where('google_id', 'google-789')->first();

    expect($model->password)->toBeNull();
});

it('returns existing user already linked to google id', function (): void {
    UserModel::factory()->create([
        'email' => 'alice@example.com',
        'google_id' => 'google-existing',
    ]);

    $useCase = app(AuthenticateWithGoogle::class);

    $result = $useCase->execute(
        googleId: 'google-existing',
        email: 'alice@example.com',
        firstName: 'Alice',
        lastName: 'Foo',
    );

    expect($result)->not->toBeNull()
        ->and($result->email)->toBe('alice@example.com');

    expect(UserModel::where('google_id', 'google-existing')->count())->toBe(1);
});

it('links google id to existing account with same email', function (): void {
    UserModel::factory()->create([
        'email' => 'bob@example.com',
        'google_id' => null,
    ]);

    $useCase = app(AuthenticateWithGoogle::class);

    $result = $useCase->execute(
        googleId: 'google-new-link',
        email: 'bob@example.com',
        firstName: 'Bob',
        lastName: 'Smith',
    );

    expect($result)->not->toBeNull()
        ->and($result->email)->toBe('bob@example.com');

    expect(UserModel::where('email', 'bob@example.com')->value('google_id'))->toBe('google-new-link');
    expect(UserModel::where('email', 'bob@example.com')->count())->toBe(1);
});

it('does not create duplicate user when same google id is used twice', function (): void {
    $useCase = app(AuthenticateWithGoogle::class);

    $useCase->execute(googleId: 'google-dup', email: 'dup@example.com', firstName: 'A', lastName: 'B');
    $useCase->execute(googleId: 'google-dup', email: 'dup@example.com', firstName: 'A', lastName: 'B');

    expect(UserModel::where('google_id', 'google-dup')->count())->toBe(1);
});
