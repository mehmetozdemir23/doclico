<?php

use App\Application\Sharing\SendShareReminders;
use App\Application\Sharing\ShareReminderNotifierInterface;
use App\Infrastructure\Persistence\Eloquent\DocumentModel;
use App\Infrastructure\Persistence\Eloquent\ShareModel;
use App\Infrastructure\Persistence\Eloquent\TemplateModel;
use App\Infrastructure\Persistence\Eloquent\UserModel;
use Mockery\MockInterface;

it('sends reminder and marks share as reminded', function (): void {
    $user = UserModel::factory()->create(['first_name' => 'Alice', 'email' => 'alice@example.com']);
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create(['name' => 'Facture #001']);
    $share = ShareModel::factory()->for($document, 'document')->create([
        'downloads_count' => 0,
        'reminded_at' => null,
        'shared_at' => now()->subDays(4),
    ]);

    $notifier = $this->mock(ShareReminderNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')
            ->once()
            ->with('alice@example.com', 'Alice', 'Facture #001', Mockery::type('string'));
    });

    $this->app->instance(ShareReminderNotifierInterface::class, $notifier);

    $sent = app(SendShareReminders::class)->execute();

    expect($sent)->toBe(1);
    $share->refresh();
    expect($share->reminded_at)->not->toBeNull();
});

it('does not send reminder for already reminded share', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    ShareModel::factory()->for($document, 'document')->create([
        'downloads_count' => 0,
        'reminded_at' => now()->subDay(),
        'shared_at' => now()->subDays(4),
    ]);

    $notifier = $this->mock(ShareReminderNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')->never();
    });

    $this->app->instance(ShareReminderNotifierInterface::class, $notifier);

    $sent = app(SendShareReminders::class)->execute();

    expect($sent)->toBe(0);
});

it('does not send reminder for downloaded share', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    ShareModel::factory()->for($document, 'document')->create([
        'downloads_count' => 1,
        'reminded_at' => null,
        'shared_at' => now()->subDays(4),
    ]);

    $notifier = $this->mock(ShareReminderNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')->never();
    });

    $this->app->instance(ShareReminderNotifierInterface::class, $notifier);

    $sent = app(SendShareReminders::class)->execute();

    expect($sent)->toBe(0);
});

it('does not send reminder for share created less than 3 days ago', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    ShareModel::factory()->for($document, 'document')->create([
        'downloads_count' => 0,
        'reminded_at' => null,
        'shared_at' => now()->subDays(2),
    ]);

    $notifier = $this->mock(ShareReminderNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')->never();
    });

    $this->app->instance(ShareReminderNotifierInterface::class, $notifier);

    $sent = app(SendShareReminders::class)->execute();

    expect($sent)->toBe(0);
});

it('does not send reminder for expired share', function (): void {
    $user = UserModel::factory()->create();
    $template = TemplateModel::factory()->create();
    $document = DocumentModel::factory()->for($user, 'user')->for($template, 'template')->create();
    ShareModel::factory()->for($document, 'document')->create([
        'downloads_count' => 0,
        'reminded_at' => null,
        'expires_at' => now()->subDay(),
        'shared_at' => now()->subDays(4),
    ]);

    $notifier = $this->mock(ShareReminderNotifierInterface::class, function (MockInterface $mock): void {
        $mock->shouldReceive('notify')->never();
    });

    $this->app->instance(ShareReminderNotifierInterface::class, $notifier);

    $sent = app(SendShareReminders::class)->execute();

    expect($sent)->toBe(0);
});
