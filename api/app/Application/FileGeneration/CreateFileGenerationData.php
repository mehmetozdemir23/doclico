<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

use App\Domain\FileGeneration\FileFormat;
use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;

final readonly class CreateFileGenerationData
{
    public function __construct(
        public TemplateId $templateId,
        public ?UserId $userId,
        public array $data,
        public FileFormat $format = FileFormat::Pdf,
    ) {}
}
