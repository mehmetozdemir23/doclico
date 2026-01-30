<?php

declare(strict_types=1);

namespace App\Infrastructure\Rendering;

use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\RendererInterface;
use App\Domain\Template\Template;
use Barryvdh\DomPDF\Facade\Pdf;

final class PdfRenderer implements RendererInterface
{
    public function render(Template $template, array $data): string
    {
        $pdf = Pdf::loadView("templates.{$template->type}", [
            'template' => $template,
            'data' => $data,
        ]);

        return $pdf->output();
    }

    public function extension(): string
    {
        return 'pdf';
    }

    public function supports(FileFormat $format): bool
    {
        return $format === FileFormat::Pdf;
    }
}
