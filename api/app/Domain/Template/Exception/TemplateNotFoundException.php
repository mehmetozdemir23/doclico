<?php

declare(strict_types=1);

namespace App\Domain\Template\Exception;

use App\Domain\Shared\Exception\EntityNotFoundException;
use App\Domain\Template\TemplateId;

final class TemplateNotFoundException extends EntityNotFoundException
{
    public function __construct(TemplateId $id)
    {
        parent::__construct($id, 'Template');
    }
}
