<?php

declare(strict_types=1);

namespace App\Domain\Shared\Exception;

use App\Domain\Shared\ValueObject\IdInterface;

abstract class EntityNotFoundException extends DomainException
{
    public function __construct(
        public readonly IdInterface $id,
        string $entityName,
    ) {
        parent::__construct("{$entityName} with ID {$id->value()} not found");
    }
}
