<?php

use App\Application\Sharing\AccessSharedDocument;
use App\Domain\Sharing\Exception\InvalidShareTokenException;
use App\Domain\Sharing\Exception\ShareExpiredException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('returns shared document data for valid token', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'expires_at' => now()->addDay(),
    ]);

    $accessSharedDocument = app(AccessSharedDocument::class);
    $result = $accessSharedDocument->execute($share->token);

    expect($result->share->id)->toBe($share->id)
        ->and($result->document->id)->toBe($document->id)
        ->and($result->template->id)->toBe($template->id);
});

it('increments download count', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'downloads_count' => 5,
        'expires_at' => now()->addDay(),
    ]);

    $accessSharedDocument = app(AccessSharedDocument::class);
    $accessSharedDocument->execute($share->token);

    $share->refresh();
    expect($share->downloads_count)->toBe(6);
});

it('throws exception for invalid token format', function (): void {
    $accessSharedDocument = app(AccessSharedDocument::class);
    $accessSharedDocument->execute('invalid-short-token');
})->throws(InvalidShareTokenException::class);

it('throws exception when share not found', function (): void {
    $accessSharedDocument = app(AccessSharedDocument::class);
    $accessSharedDocument->execute('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6');
})->throws(ShareNotFoundException::class);

it('throws exception when share is expired', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'expires_at' => now()->subDay(),
    ]);

    $accessSharedDocument = app(AccessSharedDocument::class);
    $accessSharedDocument->execute($share->token);
})->throws(ShareExpiredException::class);

it('works with share without expiration', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'expires_at' => null,
    ]);

    $accessSharedDocument = app(AccessSharedDocument::class);
    $result = $accessSharedDocument->execute($share->token);

    expect($result->share->id)->toBe($share->id);
});
