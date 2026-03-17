<?php

declare(strict_types=1);

namespace App\Domain\Document\Exception;

use DomainException;

final class DocumentDeletionForbiddenException extends DomainException
{
    public function __construct()
    {
        parent::__construct('Ce document ne peut pas être supprimé car il est soumis à une obligation légale de conservation.');
    }
}
