<?php

use App\Application\Sharing\CreateShare;
use App\Application\Sharing\CreateShareData;
use App\Domain\Document\DocumentId;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\InvalidExpirationPeriodException;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('creates a share with 24h expiration', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();

    $data = new CreateShareData(
        documentId: DocumentId::fromString($document->id),
        expiresIn: '24h',
        currentUserId: UserId::fromString($user->id),
    );

    $createShare = app(CreateShare::class);
    $share = $createShare->execute($data);

    expect($share->documentId)->toBe($document->id)
        ->and($share->token)->toHaveLength(32)
        ->and($share->expiresAt)->not->toBeNull();
});

it('creates a share without expiration', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();

    $data = new CreateShareData(
        documentId: DocumentId::fromString($document->id),
        expiresIn: 'never',
        currentUserId: UserId::fromString($user->id),
    );

    $createShare = app(CreateShare::class);
    $share = $createShare->execute($data);

    expect($share->expiresAt)->toBeNull();
});

it('throws exception when document not found', function (): void {
    $user = UserModel::factory()->create();

    $data = new CreateShareData(
        documentId: DocumentId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        expiresIn: '24h',
        currentUserId: UserId::fromString($user->id),
    );

    $createShare = app(CreateShare::class);
    $createShare->execute($data);
})->throws(DocumentNotFoundException::class);

it('throws exception for invalid expiration period', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();

    $data = new CreateShareData(
        documentId: DocumentId::fromString($document->id),
        expiresIn: 'invalid',
        currentUserId: UserId::fromString($user->id),
    );

    $createShare = app(CreateShare::class);
    $createShare->execute($data);
})->throws(InvalidExpirationPeriodException::class);

it('throws exception when user is not the document owner', function (): void {
    $owner = UserModel::factory()->create();
    $otherUser = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($owner, 'user')->for($template, 'template')->create();

    $data = new CreateShareData(
        documentId: DocumentId::fromString($document->id),
        expiresIn: '24h',
        currentUserId: UserId::fromString($otherUser->id),
    );

    $createShare = app(CreateShare::class);
    $createShare->execute($data);
})->throws(UnauthorizedException::class);
