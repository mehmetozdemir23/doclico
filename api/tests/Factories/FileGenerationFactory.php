<?php

declare(strict_types=1);

namespace Tests\Factories;

use App\Domain\FileGeneration\FileGenerationId;
use App\Infrastructure\Persistence\Eloquent\FileGenerationModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FileGenerationModel>
 */
final class FileGenerationFactory extends Factory
{
    protected $model = FileGenerationModel::class;

    public function definition(): array
    {
        return [
            'id' => FileGenerationId::generate()->value,
            'data' => [],
            'format' => 'pdf',
            'status' => 'pending',
        ];
    }
}
