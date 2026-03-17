<?php

declare(strict_types=1);

namespace App\Infrastructure\Rendering;

use App\Application\FileGeneration\RendererFactoryInterface;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\RendererInterface;
use InvalidArgumentException;

final class RendererFactory implements RendererFactoryInterface
{
    /** @var RendererInterface[] */
    private array $renderers = [];

    public function register(RendererInterface $renderer): void
    {
        $this->renderers[] = $renderer;
    }

    public function make(FileFormat $format): RendererInterface
    {
        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($format)) {
                return $renderer;
            }
        }

        throw new InvalidArgumentException("No renderer found for format: {$format->value}");
    }
}
