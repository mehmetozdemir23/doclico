<?php

declare(strict_types=1);

namespace App\Domain\Template\Exception;

use App\Domain\Shared\Exception\DomainException;

final class TemplateValidationException extends DomainException
{
    /** @param array<string, string> $errors */
    public function __construct(
        public readonly array $errors,
    ) {
        parent::__construct(array_values($errors)[0] ?? 'Template validation failed');
    }
}
