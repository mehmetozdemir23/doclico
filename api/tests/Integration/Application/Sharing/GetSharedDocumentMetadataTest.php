<?php

use App\Application\Sharing\GetSharedDocumentMetadata;
use App\Application\Sharing\SharedDocumentMetadata;
use App\Domain\Sharing\Exception\InvalidShareTokenException;
use App\Domain\Sharing\Exception\ShareExpiredException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('returns metadata for a valid token', function (): void {
    $user = UserModel::factory()->create(['first_name' => 'Jean', 'last_name' => 'Dupont']);
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create([
        'name' => 'Facture #001',
    ]);
    $share = ShareModel::factory()->for($document, 'document')->create([
        'expires_at' => now()->addDay(),
        'downloads_count' => 3,
    ]);

    $result = app(GetSharedDocumentMetadata::class)->execute($share->token);

    expect($result)->toBeInstanceOf(SharedDocumentMetadata::class)
        ->and($result->documentName)->toBe('Facture #001')
        ->and($result->emitter)->toBe('Jean Dupont')
        ->and($result->templateType)->toBeString();
});

it('does not increment download count', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'downloads_count' => 7,
        'expires_at' => now()->addDay(),
    ]);

    app(GetSharedDocumentMetadata::class)->execute($share->token);

    $share->refresh();
    expect($share->downloads_count)->toBe(7);
});

it('returns null expiry for a permanent share', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create(['expires_at' => null]);

    $result = app(GetSharedDocumentMetadata::class)->execute($share->token);

    expect($result->expiresAt)->toBeNull();
});

it('throws exception for invalid token format', function (): void {
    app(GetSharedDocumentMetadata::class)->execute('invalid-short-token');
})->throws(InvalidShareTokenException::class);

it('throws exception when share not found', function (): void {
    app(GetSharedDocumentMetadata::class)->execute('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6');
})->throws(ShareNotFoundException::class);

it('throws exception when share is expired', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create([
        'expires_at' => now()->subDay(),
    ]);

    app(GetSharedDocumentMetadata::class)->execute($share->token);
})->throws(ShareExpiredException::class);
