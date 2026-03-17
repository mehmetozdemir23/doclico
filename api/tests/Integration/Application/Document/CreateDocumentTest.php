<?php

use App\Application\Document\CreateDocument;
use App\Application\Document\CreateDocumentData;
use App\Domain\Identity\UserId;
use App\Domain\Template\Exception\TemplateNotFoundException;
use App\Domain\Template\TemplateId;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
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

it('auto-assigns a sequential number when creating a facture', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create(['type' => 'facture']);

    $document = app(CreateDocument::class)->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
    ));

    $year = (int) now()->format('Y');
    expect($document->data['numero_facture'])->toBe("FAC-{$year}-001");
});

it('auto-assigns a sequential number when creating a devis', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create(['type' => 'devis']);

    $document = app(CreateDocument::class)->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
    ));

    $year = (int) now()->format('Y');
    expect($document->data['numero_devis'])->toBe("DEV-{$year}-001");
});

it('increments the sequential number on successive creations', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create(['type' => 'facture']);
    $createDocument = app(CreateDocument::class);
    $year = (int) now()->format('Y');

    $makeData = fn (): CreateDocumentData => new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
    );

    $createDocument->execute($makeData());
    $createDocument->execute($makeData());
    $third = $createDocument->execute($makeData());

    expect($third->data['numero_facture'])->toBe("FAC-{$year}-003");
});

it('sequential numbers are independent per document type', function (): void {
    $user = UserModel::factory()->create();
    $factureTemplate = TemplateModel::factory()->create(['type' => 'facture']);
    $devisTemplate = TemplateModel::factory()->create(['type' => 'devis']);
    $createDocument = app(CreateDocument::class);
    $year = (int) now()->format('Y');

    $createDocument->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($factureTemplate->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
    ));

    $devis = $createDocument->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($devisTemplate->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
    ));

    expect($devis->data['numero_devis'])->toBe("DEV-{$year}-001");
});

it('associates client and injects client data into document', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create(['type' => 'facture']);
    $client = ClientModel::factory()->for($user, 'user')->create([
        'nom' => 'Acme Corp',
        'adresse' => '1 avenue des Champs-Élysées',
    ]);

    $document = app(CreateDocument::class)->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
        clientId: $client->id,
    ));

    expect($document->data['client_nom'])->toBe('Acme Corp')
        ->and($document->data['client_adresse'])->toBe('1 avenue des Champs-Élysées');
});

it('does not inject client data when no client_id is provided', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();

    $document = app(CreateDocument::class)->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user->id),
        name: null,
        data: [],
    ));

    expect($document->data)->not->toHaveKey('client_nom');
});

it('sequential numbers are independent per user', function (): void {
    $user1 = UserModel::factory()->create();
    $user2 = UserModel::factory()->create();
    $template = TemplateModel::factory()->create(['type' => 'facture']);
    $createDocument = app(CreateDocument::class);
    $year = (int) now()->format('Y');

    $createDocument->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user1->id),
        name: null,
        data: [],
    ));

    $user2Doc = $createDocument->execute(new CreateDocumentData(
        templateId: TemplateId::fromInt($template->id),
        userId: UserId::fromString($user2->id),
        name: null,
        data: [],
    ));

    expect($user2Doc->data['numero_facture'])->toBe("FAC-{$year}-001");
});
