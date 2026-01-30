<?php

declare(strict_types=1);

namespace App\Application\Template;

use App\Domain\Template\TemplateRepositoryInterface;

final readonly class GetAllTemplates
{
    public function __construct(
        private TemplateRepositoryInterface $templateRepository,
    ) {}

    public function execute(): TemplateListResult
    {
        $templates = $this->templateRepository->findAll();

        return TemplateResultMapper::toListResult($templates);
    }
}
