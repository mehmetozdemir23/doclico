<?php

declare(strict_types=1);

namespace App\Domain\FileGeneration\Exception;

use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\Shared\Exception\DomainException;

final class FileNotReadyException extends DomainException
{
    public function __construct(FileGenerationId $id)
    {
        parent::__construct("File generation {$id->value} is not ready for download");
    }
}
