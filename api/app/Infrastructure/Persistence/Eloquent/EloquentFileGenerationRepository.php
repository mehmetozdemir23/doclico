<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\FileGeneration\FileGeneration;
use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\FileGeneration\FileGenerationRepositoryInterface;
use App\Infrastructure\Persistence\Mapper\FileGenerationMapper;

final class EloquentFileGenerationRepository implements FileGenerationRepositoryInterface
{
    public function save(FileGeneration $fileGeneration): void
    {
        $model = FileGenerationMapper::toModel($fileGeneration);
        $model->save();
    }

    public function findById(FileGenerationId $id): ?FileGeneration
    {
        $model = FileGenerationModel::find($id->value);

        return $model ? FileGenerationMapper::toDomain($model) : null;
    }

    public function update(FileGeneration $fileGeneration): void
    {
        $model = FileGenerationModel::find($fileGeneration->id->value);

        if ($model) {
            FileGenerationMapper::updateModel($model, $fileGeneration);
            $model->save();
        }
    }
}
