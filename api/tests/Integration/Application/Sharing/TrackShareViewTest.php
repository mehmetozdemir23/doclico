<?php

use App\Application\Sharing\TrackShareView;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('increments views count on first view', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'views_count' => 0,
        'expires_at' => now()->addDay(),
    ]);

    app(TrackShareView::class)->execute($share->token);

    $share->refresh();
    expect($share->views_count)->toBe(1)
        ->and($share->first_viewed_at)->not->toBeNull();
});

it('does not overwrite first_viewed_at on subsequent views', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $firstView = now()->subHour();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'views_count' => 1,
        'first_viewed_at' => $firstView,
        'expires_at' => now()->addDay(),
    ]);

    app(TrackShareView::class)->execute($share->token);

    $share->refresh();
    expect($share->views_count)->toBe(2)
        ->and($share->first_viewed_at->toDateTimeString())->toBe($firstView->toDateTimeString());
});

it('does nothing for an invalid token format', function (): void {
    app(TrackShareView::class)->execute('invalid-short-token');

    expect(ShareModel::count())->toBe(0);
});

it('does nothing when share not found', function (): void {
    app(TrackShareView::class)->execute('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6');

    expect(ShareModel::count())->toBe(0);
});

it('does nothing when share is expired', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'views_count' => 0,
        'expires_at' => now()->subDay(),
    ]);

    app(TrackShareView::class)->execute($share->token);

    $share->refresh();
    expect($share->views_count)->toBe(0);
});
