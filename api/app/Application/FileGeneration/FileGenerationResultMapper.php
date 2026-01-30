<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

use App\Domain\FileGeneration\FileGeneration;

final class FileGenerationResultMapper
{
    public static function toResult(FileGeneration $fileGeneration): FileGenerationResult
    {
        return new FileGenerationResult(
            id: $fileGeneration->id->value,
            templateId: $fileGeneration->templateId->value,
            userId: $fileGeneration->userId?->value,
            format: $fileGeneration->format->value,
            status: $fileGeneration->status()->value,
            filePath: $fileGeneration->filePath(),
            error: $fileGeneration->error(),
        );
    }
}
