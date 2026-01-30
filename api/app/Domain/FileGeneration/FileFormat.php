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
}
