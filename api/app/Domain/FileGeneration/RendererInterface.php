<?php

declare(strict_types=1);

namespace App\Domain\FileGeneration;

use App\Domain\Template\Template;

interface RendererInterface
{
    public function render(Template $template, array $data): string;

    public function extension(): string;

    public function supports(FileFormat $format): bool;
}
