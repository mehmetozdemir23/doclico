<?php

declare(strict_types=1);

namespace App\Application\Template;

final readonly class TemplateListResult
{
    /** @param TemplateResult[] $templates */
    public function __construct(
        public array $templates,
    ) {}
}
