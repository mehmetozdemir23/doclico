<?php

use App\Application\Sharing\GetDocumentShares;
use App\Domain\Document\DocumentId;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('returns shares for document owner', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share1 = ShareModel::factory()->for($document, 'document')->create();
    $share2 = ShareModel::factory()->for($document, 'document')->create();

    $getDocumentShares = app(GetDocumentShares::class);
    $shares = $getDocumentShares->execute(
        DocumentId::fromString($document->id),
        UserId::fromString($user->id)
    );

    expect($shares->shares)->toHaveCount(2);
});

it('returns empty array when document has no shares', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();

    $getDocumentShares = app(GetDocumentShares::class);
    $shares = $getDocumentShares->execute(
        DocumentId::fromString($document->id),
        UserId::fromString($user->id)
    );

    expect($shares->shares)->toBeEmpty();
});

it('throws exception when document not found', function (): void {
    $user = UserModel::factory()->create();

    $getDocumentShares = app(GetDocumentShares::class);
    $getDocumentShares->execute(
        DocumentId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        UserId::fromString($user->id)
    );
})->throws(DocumentNotFoundException::class);

it('throws exception when user is not the document owner', function (): void {
    $owner = UserModel::factory()->create();
    $otherUser = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($owner, 'user')->for($template, 'template')->create();

    $getDocumentShares = app(GetDocumentShares::class);
    $getDocumentShares->execute(
        DocumentId::fromString($document->id),
        UserId::fromString($otherUser->id)
    );
})->throws(UnauthorizedException::class);
