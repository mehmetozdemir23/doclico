<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

use App\Domain\FileGeneration\Exception\FileGenerationNotFoundException;
use App\Domain\FileGeneration\FileGeneration;
use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\FileGeneration\FileGenerationRepositoryInterface;
use App\Domain\FileGeneration\RendererInterface;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;
use Throwable;

final readonly class ProcessFileGeneration
{
    public function __construct(
        private FileGenerationRepositoryInterface $fileGenerationRepository,
        private TemplateRepositoryInterface $templateRepository,
        private RendererInterface $renderer,
        private FileStorageInterface $storage,
    ) {}

    public function execute(FileGenerationId $fileGenerationId): FileGenerationResult
    {
        $fileGeneration = $this->fileGenerationRepository->findById($fileGenerationId);

        if (! $fileGeneration instanceof FileGeneration) {
            throw new FileGenerationNotFoundException($fileGenerationId);
        }

        $template = $this->templateRepository->findById($fileGeneration->templateId);

        if (! $template instanceof Template) {
            $fileGeneration->markFailed('Template not found');
            $this->fileGenerationRepository->update($fileGeneration);

            return FileGenerationResultMapper::toResult($fileGeneration);
        }

        try {
            $fileGeneration->markProcessing();
            $this->fileGenerationRepository->update($fileGeneration);

            $content = $this->renderer->render($template, $fileGeneration->data);
            $path = "generated_documents/{$fileGeneration->id}.{$this->renderer->extension()}";

            $this->storage->put($path, $content);

            $fileGeneration->markCompleted($path);
            $this->fileGenerationRepository->update($fileGeneration);
        } catch (Throwable $e) {
            $fileGeneration->markFailed($e->getMessage());
            $this->fileGenerationRepository->update($fileGeneration);
        }

        return FileGenerationResultMapper::toResult($fileGeneration);
    }
}
