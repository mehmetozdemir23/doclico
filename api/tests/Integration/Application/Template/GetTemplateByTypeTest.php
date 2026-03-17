<?php

use App\Application\Template\GetTemplateByType;
use App\Domain\Template\Exception\TemplateTypeNotFoundException;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;

it('returns template by type', function (): void {
    TemplateModel::factory()->create(['type' => 'facture']);

    $getTemplateByType = app(GetTemplateByType::class);
    $template = $getTemplateByType->execute('facture');

    expect($template)->not->toBeNull()
        ->and($template->type)->toBe('facture');
});

it('throws when template type not found', function (): void {
    app(GetTemplateByType::class)->execute('non-existing');
})->throws(TemplateTypeNotFoundException::class);
