<?php

use App\Application\Identity\ExportUserData;
use App\Domain\Identity\UserId;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;

it('exports profile data', function (): void {
    $user = UserModel::factory()->create([
        'first_name' => 'Jean',
        'last_name' => 'Dupont',
        'email' => 'jean@example.com',
        'siret' => '12345678901234',
    ]);

    $result = app(ExportUserData::class)->execute(UserId::fromString($user->id));

    expect($result['profile'])
        ->toMatchArray([
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'email' => 'jean@example.com',
            'siret' => '12345678901234',
        ]);
});

it('exports clients belonging to the user', function (): void {
    $user = UserModel::factory()->create();
    $other = UserModel::factory()->create();

    ClientModel::factory()->for($user, 'user')->create(['nom' => 'Client A']);
    ClientModel::factory()->for($user, 'user')->create(['nom' => 'Client B']);
    ClientModel::factory()->for($other, 'user')->create(['nom' => 'Client C']);

    $result = app(ExportUserData::class)->execute(UserId::fromString($user->id));

    expect($result['clients'])->toHaveCount(2)
        ->and(array_column($result['clients'], 'nom'))->toContain('Client A', 'Client B');
});

it('exports documents belonging to the user', function (): void {
    $user = UserModel::factory()->create();
    $other = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();

    DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create(['name' => 'Doc 1']);
    DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create(['name' => 'Doc 2']);
    DocumentModel::factory()->for($other, 'user')->for($template, 'template')->create(['name' => 'Doc 3']);

    $result = app(ExportUserData::class)->execute(UserId::fromString($user->id));

    expect($result['documents'])->toHaveCount(2)
        ->and(array_column($result['documents'], 'name'))->toContain('Doc 1', 'Doc 2');
});

it('exports empty collections when user has no clients or documents', function (): void {
    $user = UserModel::factory()->create();

    $result = app(ExportUserData::class)->execute(UserId::fromString($user->id));

    expect($result['clients'])->toBeEmpty()
        ->and($result['documents'])->toBeEmpty();
});

it('includes exported_at timestamp', function (): void {
    $user = UserModel::factory()->create();

    $result = app(ExportUserData::class)->execute(UserId::fromString($user->id));

    expect($result['exported_at'])->toBeString()->not->toBeEmpty();
});
