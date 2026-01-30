<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<UserModel>
 */
final class UserFactory extends Factory
{
    protected $model = UserModel::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'id' => UserId::generate()->value,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
