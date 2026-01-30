<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Domain\Document\DocumentId;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocumentModel>
 */
final class DocumentFactory extends Factory
{
    protected $model = DocumentModel::class;

    public function definition(): array
    {
        return [
            'id' => DocumentId::generate()->value,
            'name' => fake()->sentence(),
            'data' => [],
        ];
    }
}
