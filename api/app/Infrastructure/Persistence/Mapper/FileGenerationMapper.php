<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mapper;

use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\FileGeneration;
use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\FileGeneration\FileGenerationStatus;
use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;
use App\Infrastructure\Persistence\Eloquent\FileGenerationModel;

final class FileGenerationMapper
{
    public static function toDomain(FileGenerationModel $model): FileGeneration
    {
        return new FileGeneration(
            id: FileGenerationId::fromString($model->id),
            templateId: TemplateId::fromInt($model->template_id),
            userId: $model->user_id ? UserId::fromString($model->user_id) : null,
            data: $model->data ?? [],
            format: FileFormat::from($model->format),
            status: FileGenerationStatus::from($model->status),
            filePath: $model->file_path,
            error: $model->error,
        );
    }

    public static function toModel(FileGeneration $entity): FileGenerationModel
    {
        $model = new FileGenerationModel;
        $model->id = $entity->id->value;
        $model->template_id = $entity->templateId->value;
        $model->user_id = $entity->userId?->value;
        $model->data = $entity->data;
        $model->format = $entity->format->value;
        $model->status = $entity->status()->value;
        $model->file_path = $entity->filePath();
        $model->error = $entity->error();

        return $model;
    }

    public static function updateModel(FileGenerationModel $model, FileGeneration $entity): void
    {
        $model->status = $entity->status()->value;
        $model->file_path = $entity->filePath();
        $model->error = $entity->error();
    }
}
