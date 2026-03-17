<?php

use App\Application\Identity\RegisterUser;
use App\Application\Identity\RegisterUserData;
use App\Domain\Identity\Exception\EmailAlreadyExistsException;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseHas;

it('registers a new user', function (): void {
    $data = new RegisterUserData(
        firstName: 'John',
        lastName: 'Doe',
        email: 'john@example.com',
        password: 'password123',
    );

    $registerUser = app(RegisterUser::class);
    $user = $registerUser->execute($data);

    expect($user->firstName)->toBe('John')
        ->and($user->lastName)->toBe('Doe')
        ->and($user->email)->toBe('john@example.com');

    assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'consent_policy_version' => RegisterUser::POLICY_VERSION,
    ]);

    $model = UserModel::where('email', 'john@example.com')->first();
    expect($model->consent_accepted_at)->not->toBeNull();
});

it('throws exception when email already exists', function (): void {
    UserModel::factory()->create(['email' => 'existing@example.com']);

    $data = new RegisterUserData(
        firstName: 'John',
        lastName: 'Doe',
        email: 'existing@example.com',
        password: 'password123',
    );

    $registerUser = app(RegisterUser::class);
    $registerUser->execute($data);
})->throws(EmailAlreadyExistsException::class);
