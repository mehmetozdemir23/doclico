<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Domain\Client\ClientId;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClientModel>
 */
final class ClientFactory extends Factory
{
    protected $model = ClientModel::class;

    public function definition(): array
    {
        return [
            'id' => ClientId::generate()->value,
            'user_id' => UserFactory::new()->create()->id,
            'nom' => fake()->company(),
            'adresse' => fake()->address(),
            'email' => fake()->safeEmail(),
            'telephone' => fake()->phoneNumber(),
            'siret' => null,
        ];
    }
}
