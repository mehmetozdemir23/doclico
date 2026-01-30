<?php

use App\Application\Template\GetAllTemplates;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;

it('returns all templates', function (): void {
    TemplateModel::factory()->count(3)->create();

    $getAllTemplates = app(GetAllTemplates::class);
    $templates = $getAllTemplates->execute();

    expect($templates->templates)->toHaveCount(3);
});

it('returns empty array when no templates exist', function (): void {
    $getAllTemplates = app(GetAllTemplates::class);
    $templates = $getAllTemplates->execute();

    expect($templates->templates)->toBeEmpty();
});
