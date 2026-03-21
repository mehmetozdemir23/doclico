<?php

use App\Application\Sharing\SendShareNotification;
use App\Application\Sharing\ShareNotifierInterface;
use App\Domain\Identity\UserId;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\ShareNotFoundException;
use App\Domain\Sharing\Exception\ShareNotificationUnavailableException;
use App\Domain\Sharing\ShareId;
use App\Infrastructure\Persistence\Eloquent\ClientModel;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Mockery\MockInterface;

it('calls notifier with correct arguments', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $client = ClientModel::factory()->for($user, 'user')->create([
        'nom' => 'Acme Corp',
        'email' => 'client@acme.com',
    ]);
    $document = DocumentModel::factory()
        ->for($user, 'user')
        ->for($template, 'template')
        ->for($client, 'client')
        ->create(['name' => 'Facture #001']);
    $share = ShareModel::factory()->for($document, 'document')->create();

    $notifier = $this->mock(ShareNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')
            ->once()
            ->with('client@acme.com', 'Acme Corp', 'Facture #001', Mockery::type('string'));
    });

    $this->app->instance(ShareNotifierInterface::class, $notifier);

    app(SendShareNotification::class)->execute(
        ShareId::fromString($share->id),
        UserId::fromString($user->id),
    );
});

it('throws when share is not found', function (): void {
    $user = UserModel::factory()->create();

    app(SendShareNotification::class)->execute(
        ShareId::fromString('550e8400-e29b-41d4-a716-446655440000'),
        UserId::fromString($user->id),
    );
})->throws(ShareNotFoundException::class);

it('throws when user is not the document owner', function (): void {
    $owner = UserModel::factory()->create();
    $otherUser = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($owner, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create();

    $notifier = $this->mock(ShareNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')->never();
    });

    $this->app->instance(ShareNotifierInterface::class, $notifier);

    app(SendShareNotification::class)->execute(
        ShareId::fromString($share->id),
        UserId::fromString($otherUser->id),
    );
})->throws(UnauthorizedException::class);

it('throws when document has no client', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    $share = ShareModel::factory()->for($document, 'document')->create();

    $notifier = $this->mock(ShareNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')->never();
    });

    $this->app->instance(ShareNotifierInterface::class, $notifier);

    app(SendShareNotification::class)->execute(
        ShareId::fromString($share->id),
        UserId::fromString($user->id),
    );
})->throws(ShareNotificationUnavailableException::class);

it('throws when client has no email', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $client = ClientModel::factory()->for($user, 'user')->create(['email' => null]);
    $document = DocumentModel::factory()
        ->for($user, 'user')
        ->for($template, 'template')
        ->for($client, 'client')
        ->create();
    $share = ShareModel::factory()->for($document, 'document')->create();

    $notifier = $this->mock(ShareNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')->never();
    });

    $this->app->instance(ShareNotifierInterface::class, $notifier);

    app(SendShareNotification::class)->execute(
        ShareId::fromString($share->id),
        UserId::fromString($user->id),
    );
})->throws(ShareNotificationUnavailableException::class);
