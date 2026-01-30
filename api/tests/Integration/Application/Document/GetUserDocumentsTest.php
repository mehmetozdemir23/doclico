<?php

use App\Application\Document\GetUserDocuments;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('returns user documents', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();

    DocumentModel::factory()->count(3)->for($user, 'user')->for($template, 'template')->create();

    $getUserDocuments = app(GetUserDocuments::class);
    $documents = $getUserDocuments->execute(UserId::fromString($user->id));

    expect($documents->documents)->toHaveCount(3);
});

it('returns empty array when user has no documents', function (): void {
    $user = UserModel::factory()->create();

    $getUserDocuments = app(GetUserDocuments::class);
    $documents = $getUserDocuments->execute(UserId::fromString($user->id));

    expect($documents->documents)->toBeEmpty();
});

it('does not return other users documents', function (): void {
    $user1 = UserModel::factory()->create();
    $user2 = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();

    DocumentModel::factory()->count(2)->for($user1, 'user')->for($template, 'template')->create();
    DocumentModel::factory()->count(3)->for($user2, 'user')->for($template, 'template')->create();

    $getUserDocuments = app(GetUserDocuments::class);
    $documents = $getUserDocuments->execute(UserId::fromString($user1->id));

    expect($documents->documents)->toHaveCount(2);
});
