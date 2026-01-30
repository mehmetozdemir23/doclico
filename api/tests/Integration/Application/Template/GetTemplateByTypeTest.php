<?php

use App\Application\Template\GetTemplateByType;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;

it('returns template by type', function (): void {
    TemplateModel::factory()->create(['type' => 'honneur']);

    $getTemplateByType = app(GetTemplateByType::class);
    $template = $getTemplateByType->execute('honneur');

    expect($template)->not->toBeNull()
        ->and($template->type)->toBe('honneur');
});

it('returns null when template not found', function (): void {
    $getTemplateByType = app(GetTemplateByType::class);
    $template = $getTemplateByType->execute('non-existing');

    expect($template)->toBeNull();
});
