<?php

use App\Application\Sharing\RevokeShare;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Domain\Sharing\ShareId;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

use function Pest\Laravel\assertDatabaseMissing;

it('revokes a share', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create();

    $revokeShare = app(RevokeShare::class);
    $revokeShare->execute(
        ShareId::fromString($share->id),
        UserId::fromString($user->id)
    );

    assertDatabaseMissing('document_shares', ['id' => $share->id]);
});

it('throws exception when share not found', function (): void {
    $user = UserModel::factory()->create();

    $revokeShare = app(RevokeShare::class);
    $revokeShare->execute(
        ShareId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        UserId::fromString($user->id)
    );
})->throws(ShareNotFoundException::class);

it('throws exception when user is not the document owner', function (): void {
    $owner = UserModel::factory()->create();
    $otherUser = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($owner, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create();

    $revokeShare = app(RevokeShare::class);
    $revokeShare->execute(
        ShareId::fromString($share->id),
        UserId::fromString($otherUser->id)
    );
})->throws(UnauthorizedException::class);
