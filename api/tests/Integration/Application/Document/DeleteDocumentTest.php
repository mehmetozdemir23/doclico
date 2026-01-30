<?php

use App\Application\Document\DeleteDocument;
use App\Domain\Document\DocumentId;
use App\Domain\Document\Exception\DocumentNotFoundException;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseMissing;

it('deletes a document', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();

    $deleteDocument = app(DeleteDocument::class);
    $deleteDocument->execute(
        DocumentId::fromString($document->id),
        UserId::fromString($user->id)
    );

    assertDatabaseMissing('documents', ['id' => $document->id]);
});

it('throws exception when document not found', function (): void {
    $user = UserModel::factory()->create();

    $deleteDocument = app(DeleteDocument::class);
    $deleteDocument->execute(
        DocumentId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        UserId::fromString($user->id)
    );
})->throws(DocumentNotFoundException::class);

it('throws exception when user is not the owner', function (): void {
    $owner = UserModel::factory()->create();
    $otherUser = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($owner, 'user')->for($template, 'template')->create();

    $deleteDocument = app(DeleteDocument::class);
    $deleteDocument->execute(
        DocumentId::fromString($document->id),
        UserId::fromString($otherUser->id)
    );
})->throws(UnauthorizedException::class);
