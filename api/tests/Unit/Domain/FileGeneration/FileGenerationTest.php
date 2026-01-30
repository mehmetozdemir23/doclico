<?php

use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\FileGeneration;
use App\Domain\FileGeneration\FileGenerationId;
use App\Domain\FileGeneration\FileGenerationStatus;
use App\Domain\Identity\UserId;
use App\Domain\Template\TemplateId;

it('creates a file generation with pending status', function (): void {
    $fileGeneration = new FileGeneration(
        id: FileGenerationId::generate(),
        templateId: TemplateId::fromInt(1),
        userId: UserId::generate(),
        data: ['key' => 'value'],
        format: FileFormat::Pdf,
    );

    expect($fileGeneration->status())->toBe(FileGenerationStatus::Pending)
        ->and($fileGeneration->filePath())->toBeNull()
        ->and($fileGeneration->error())->toBeNull()
        ->and($fileGeneration->isCompleted())->toBeFalse()
        ->and($fileGeneration->isFailed())->toBeFalse();
});

it('allows null user id for guest generations', function (): void {
    $fileGeneration = new FileGeneration(
        id: FileGenerationId::generate(),
        templateId: TemplateId::fromInt(1),
        userId: null,
        data: [],
        format: FileFormat::Pdf,
    );

    expect($fileGeneration->userId)->toBeNull();
});

it('marks as processing', function (): void {
    $fileGeneration = new FileGeneration(
        id: FileGenerationId::generate(),
        templateId: TemplateId::fromInt(1),
        userId: UserId::generate(),
        data: [],
        format: FileFormat::Pdf,
    );

    $fileGeneration->markProcessing();

    expect($fileGeneration->status())->toBe(FileGenerationStatus::Processing);
});

it('marks as completed with file path', function (): void {
    $fileGeneration = new FileGeneration(
        id: FileGenerationId::generate(),
        templateId: TemplateId::fromInt(1),
        userId: UserId::generate(),
        data: [],
        format: FileFormat::Pdf,
    );

    $fileGeneration->markCompleted('documents/test.pdf');

    expect($fileGeneration->status())->toBe(FileGenerationStatus::Completed)
        ->and($fileGeneration->filePath())->toBe('documents/test.pdf')
        ->and($fileGeneration->isCompleted())->toBeTrue()
        ->and($fileGeneration->isFailed())->toBeFalse();
});

it('marks as failed with error', function (): void {
    $fileGeneration = new FileGeneration(
        id: FileGenerationId::generate(),
        templateId: TemplateId::fromInt(1),
        userId: UserId::generate(),
        data: [],
        format: FileFormat::Pdf,
    );

    $fileGeneration->markFailed('Template not found');

    expect($fileGeneration->status())->toBe(FileGenerationStatus::Failed)
        ->and($fileGeneration->error())->toBe('Template not found')
        ->and($fileGeneration->isCompleted())->toBeFalse()
        ->and($fileGeneration->isFailed())->toBeTrue();
});
