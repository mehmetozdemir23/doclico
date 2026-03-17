<?php

declare(strict_types=1);

namespace App\Domain\FileGeneration;

enum FileFormat: string
{
    case Pdf = 'pdf';
    case Docx = 'docx';

    public function extension(): string
    {
        return $this->value;
    }

    public function mimeType(): string
    {
        return match ($this) {
            self::Pdf => 'application/pdf',
            self::Docx => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        };
    }
}
