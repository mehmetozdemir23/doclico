<?php

use App\Domain\Template\Template;
use App\Domain\Template\TemplateId;

it('creates a template', function (): void {
    $templateId = TemplateId::fromInt(1);
    $template = new Template(
        id: $templateId,
        type: 'facture',
        name: 'Facture',
        category: 'Facturation',
        icon: 'Receipt',
        fields: [],
    );

    expect($template->id)->toBe($templateId)
        ->and($template->type)->toBe('facture')
        ->and($template->popular)->toBeFalse();
});

it('detects required fields', function (): void {
    $templateWithRequired = new Template(
        id: TemplateId::fromInt(1),
        type: 'test',
        name: 'Test',
        category: 'Test',
        icon: 'icon',
        fields: [
            ['name' => 'field1', 'required' => true],
            ['name' => 'field2', 'required' => false],
        ],
    );

    $templateWithoutRequired = new Template(
        id: TemplateId::fromInt(2),
        type: 'test2',
        name: 'Test 2',
        category: 'Test',
        icon: 'icon',
        fields: [
            ['name' => 'field1', 'required' => false],
        ],
    );

    $templateWithEmptyFields = new Template(
        id: TemplateId::fromInt(3),
        type: 'test3',
        name: 'Test 3',
        category: 'Test',
        icon: 'icon',
        fields: [],
    );

    expect($templateWithRequired->hasRequiredFields())->toBeTrue()
        ->and($templateWithoutRequired->hasRequiredFields())->toBeFalse()
        ->and($templateWithEmptyFields->hasRequiredFields())->toBeFalse();
});

it('validates data with all required fields', function (): void {
    $template = new Template(
        id: TemplateId::fromInt(1),
        type: 'test',
        name: 'Test',
        category: 'Test',
        icon: 'icon',
        fields: [
            ['name' => 'full_name', 'required' => true],
            ['name' => 'address', 'required' => true],
        ],
    );

    $validData = ['full_name' => 'John Doe', 'address' => '123 Main St'];
    $errors = $template->validateData($validData);

    expect($errors)->toBeEmpty();
});

it('returns errors for missing required fields', function (): void {
    $template = new Template(
        id: TemplateId::fromInt(1),
        type: 'test',
        name: 'Test',
        category: 'Test',
        icon: 'icon',
        fields: [
            ['name' => 'full_name', 'required' => true],
            ['name' => 'address', 'required' => true],
            ['name' => 'notes', 'required' => false],
        ],
    );

    $incompleteData = ['full_name' => 'John Doe'];
    $errors = $template->validateData($incompleteData);

    expect($errors)->toHaveKey('address')
        ->and($errors)->not->toHaveKey('full_name')
        ->and($errors)->not->toHaveKey('notes');
});

it('treats empty string as missing value', function (): void {
    $template = new Template(
        id: TemplateId::fromInt(1),
        type: 'test',
        name: 'Test',
        category: 'Test',
        icon: 'icon',
        fields: [
            ['name' => 'full_name', 'required' => true],
        ],
    );

    $dataWithEmptyString = ['full_name' => ''];
    $errors = $template->validateData($dataWithEmptyString);

    expect($errors)->toHaveKey('full_name');
});
