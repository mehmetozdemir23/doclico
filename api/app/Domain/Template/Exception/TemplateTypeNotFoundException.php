<?php

declare(strict_types=1);

namespace App\Domain\Template\Exception;

use App\Domain\Shared\Exception\DomainException;

final class TemplateTypeNotFoundException extends DomainException
{
    public function __construct(string $type)
    {
        parent::__construct("Template of type '{$type}' not found");
    }
}
