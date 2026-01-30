<?php

use App\Application\FileGeneration\CreateFileGeneration;
use App\Application\FileGeneration\CreateFileGenerationData;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\FileGenerationStatus;
use App\Domain\Identity\UserId;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\Exception\TemplateValidationException;
use App\Domain\Template\TemplateId;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseHas;

it('creates a file generation', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create([
        'fields' => [
            ['name' => 'full_name', 'required' => true],
        ],
    ]);

    $data = new CreateFileGenerationData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        data: ['full_name' => 'John Doe'],
        format: FileFormat::Pdf,
    );

    $createFileGeneration = app(CreateFileGeneration::class);
    $fileGeneration = $createFileGeneration->execute($data);

    expect($fileGeneration->templateId)->toBe($template->id)
        ->and($fileGeneration->userId)->toBe($user->id)
        ->and($fileGeneration->status)->toBe(FileGenerationStatus::Pending->value)
        ->and($fileGeneration->format)->toBe(FileFormat::Pdf->value);

    assertDatabaseHas('file_generations', [
        'id' => $fileGeneration->id,
        'template_id' => $template->id,
        'status' => 'pending',
    ]);
});

it('creates a file generation for guest user', function (): void {
    $template = TemplateModel::factory()->create(['fields' => []]);

    $data = new CreateFileGenerationData(
        templateId: TemplateId::fromInt($template->id),
        userId: null,
        data: [],
        format: FileFormat::Pdf,
    );

    $createFileGeneration = app(CreateFileGeneration::class);
    $fileGeneration = $createFileGeneration->execute($data);

    expect($fileGeneration->userId)->toBeNull();
});

it('throws exception when template not found', function (): void {
    $data = new CreateFileGenerationData(
        templateId: TemplateId::fromInt(9999),
        userId: null,
        data: [],
        format: FileFormat::Pdf,
    );

    $createFileGeneration = app(CreateFileGeneration::class);
    $createFileGeneration->execute($data);
})->throws(TemplateNotFoundException::class);

it('throws exception when required field is missing', function (): void {
    $template = TemplateModel::factory()->create([
        'fields' => [
            ['name' => 'full_name', 'required' => true],
        ],
    ]);

    $data = new CreateFileGenerationData(
        templateId: TemplateId::fromInt($template->id),
        userId: null,
        data: [],
        format: FileFormat::Pdf,
    );

    $createFileGeneration = app(CreateFileGeneration::class);
    $createFileGeneration->execute($data);
})->throws(TemplateValidationException::class);
