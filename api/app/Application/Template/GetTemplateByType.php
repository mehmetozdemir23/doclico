<?php

declare(strict_types=1);

namespace App\Application\Template;

use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class GetTemplateByType
{
    public function __construct(
        private TemplateRepositoryInterface $templateRepository,
    ) {}

    public function execute(string $type): ?TemplateResult
    {
        $template = $this->templateRepository->findByType($type);

        if (! $template instanceof Template) {
            return null;
        }

        return TemplateResultMapper::toResult($template);
    }
}
