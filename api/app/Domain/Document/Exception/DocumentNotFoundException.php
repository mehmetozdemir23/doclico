<?php

declare(strict_types=1);

namespace App\Domain\Document\Exception;

use App\Domain\Document\DocumentId;
use App\Domain\Shared\Exception\EntityNotFoundException;

final class DocumentNotFoundException extends EntityNotFoundException
{
    public function __construct(DocumentId $id)
    {
        parent::__construct($id, 'Document');
    }
}
