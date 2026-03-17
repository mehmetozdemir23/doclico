<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\RendererInterface;

interface RendererFactoryInterface
{
    public function make(FileFormat $format): RendererInterface;
}
