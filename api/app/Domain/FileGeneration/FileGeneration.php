<?php

declare(strict_types=1);

namespace App\Domain\FileGeneration;

use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;

final class FileGeneration
{
    public function __construct(
        public readonly FileGenerationId $id,
        public readonly TemplateId $templateId,
        public readonly ?UserId $userId,
        public readonly array $data,
        public readonly FileFormat $format,
        private FileGenerationStatus $status = FileGenerationStatus::Pending,
        private ?string $filePath = null,
        private ?string $error = null,
    ) {}

    public function status(): FileGenerationStatus
    {
        return $this->status;
    }

    public function filePath(): ?string
    {
        return $this->filePath;
    }

    public function error(): ?string
    {
        return $this->error;
    }

    public function markProcessing(): void
    {
        $this->status = FileGenerationStatus::Processing;
    }

    public function markCompleted(string $filePath): void
    {
        $this->status = FileGenerationStatus::Completed;
        $this->filePath = $filePath;
    }

    public function markFailed(string $error): void
    {
        $this->status = FileGenerationStatus::Failed;
        $this->error = $error;
    }

    public function isCompleted(): bool
    {
        return $this->status === FileGenerationStatus::Completed;
    }

    public function isFailed(): bool
    {
        return $this->status === FileGenerationStatus::Failed;
    }
}
