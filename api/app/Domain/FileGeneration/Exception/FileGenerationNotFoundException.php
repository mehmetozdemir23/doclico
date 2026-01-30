<?php

declare(strict_types=1);

namespace App\Domain\FileGeneration\Exception;

use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\Shared\Exception\EntityNotFoundException;

final class FileGenerationNotFoundException extends EntityNotFoundException
{
    public function __construct(FileGenerationId $id)
    {
        parent::__construct($id, 'FileGeneration');
    }
}
