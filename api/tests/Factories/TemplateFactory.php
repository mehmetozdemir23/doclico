<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TemplateModel>
 */
final class TemplateFactory extends Factory
{
    protected $model = TemplateModel::class;

    public function definition(): array
    {
        return [
            'type' => fake()->unique()->slug(2),
            'name' => fake()->word(),
            'category' => fake()->word(),
            'icon' => null,
            'fields' => [],
            'popular' => false,
        ];
    }
}
