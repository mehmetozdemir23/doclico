<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

use App\Domain\FileGeneration\Exception\FileGenerationNotFoundException;
use App\Domain\FileGeneration\Exception\FileNotReadyException;
use App\Domain\FileGeneration\FileGeneration;
use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\FileGeneration\FileGenerationRepositoryInterface;

final readonly class GetFileGenerationForDownload
{
    public function __construct(
        private FileGenerationRepositoryInterface $fileGenerationRepository,
    ) {}

    public function execute(FileGenerationId $fileGenerationId): FileGenerationResult
    {
        $fileGeneration = $this->fileGenerationRepository->findById($fileGenerationId);

        if (! $fileGeneration instanceof FileGeneration) {
            throw new FileGenerationNotFoundException($fileGenerationId);
        }

        if (! $fileGeneration->isCompleted() || $fileGeneration->filePath() === null) {
            throw new FileNotReadyException($fileGenerationId);
        }

        return FileGenerationResultMapper::toResult($fileGeneration);
    }
}
