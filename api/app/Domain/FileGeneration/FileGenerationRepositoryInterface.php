<?php

declare(strict_types=1);

namespace App\Domain\FileGeneration;

interface FileGenerationRepositoryInterface
{
    public function save(FileGeneration $fileGeneration): void;

    public function findById(FileGenerationId $id): ?FileGeneration;

    public function update(FileGeneration $fileGeneration): void;
}
