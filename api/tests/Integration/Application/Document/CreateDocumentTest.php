<?php

use App\Application\Document\CreateDocument;
use App\Application\Document\CreateDocumentData;
use App\Domain\Identity\UserId;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\TemplateId;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('creates a document with provided name', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();

    $data = new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        name: 'Mon Document',
        data: ['field1' => 'value1'],
    );

    $createDocument = app(CreateDocument::class);
    $document = $createDocument->execute($data);

    expect($document->name)->toBe('Mon Document')
        ->and($document->templateId)->toBe($template->id)
        ->and($document->data)->toBe(['field1' => 'value1']);
});

it('creates a document with generated name when not provided', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create(['name' => 'Attestation']);

    $data = new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
    );

    $createDocument = app(CreateDocument::class);
    $document = $createDocument->execute($data);

    expect($document->name)->toContain('Attestation');
});

it('throws exception when template not found', function (): void {
    $user = UserModel::factory()->create();

    $data = new CreateDocumentData(
        templateId: TemplateId::fromInt(9999),
        userId: UserId::fromString($user->id),
        name: 'Test',
        data: [],
    );

    $createDocument = app(CreateDocument::class);
    $createDocument->execute($data);
})->throws(TemplateNotFoundException::class, 'Template with ID 9999 not found');
