<?php

declare(strict_types=1);

namespace App\Application\FileGeneration;

use App\Domain\FileGeneration\FileGeneration;
use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\FileGeneration\FileGenerationRepositoryInterface;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\Exception\TemplateValidationException;
use App\Domain\Template\Template;
use App\Domain\Template\TemplateRepositoryInterface;

final readonly class CreateFileGeneration
{
    public function __construct(
        private FileGenerationRepositoryInterface $fileGenerationRepository,
        private TemplateRepositoryInterface $templateRepository,
    ) {}

    public function execute(CreateFileGenerationData $data): FileGenerationResult
    {
        $template = $this->templateRepository->findById($data->templateId);

        if (! $template instanceof Template) {
            throw new TemplateNotFoundException($data->templateId);
        }

        $errors = $template->validateData($data->data);

        if ($errors !== []) {
            throw new TemplateValidationException($errors);
        }

        $fileGeneration = new FileGeneration(
            id: FileGenerationId::generate(),
            templateId: $data->templateId,
            userId: $data->userId,
            data: $data->data,
            format: $data->format,
        );

        $this->fileGenerationRepository->save($fileGeneration);

        return FileGenerationResultMapper::toResult($fileGeneration);
    }
}
