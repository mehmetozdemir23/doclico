<?php

namespace App\Providers;

use App\Application\FileGeneration\RendererFactoryInterface;
use App\Application\Identity\SessionManagerInterface;
use App\Application\Sharing\AccessSharedDocument;
use App\Application\Sharing\CreateShare;
use App\Application\Sharing\GetDocumentShares;
use App\Application\Sharing\GetSharedDocumentMetadata;
use App\Application\Sharing\RenderSharedDocument;
use App\Application\Sharing\SendShareNotification;
use App\Application\Sharing\SendShareReminders;
use App\Application\Sharing\ShareNotifierInterface;
use App\Application\Sharing\ShareReminderNotifierInterface;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Document\DocumentAuthorizationServiceInterface;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Identity\UserRepositoryInterface;
use App\Domain\Sharing\ShareAuthorizationServiceInterface;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Template\TemplateRepositoryInterface;
use App\Infrastructure\Auth\LaravelSessionManager;
use App\Infrastructure\Authorization\DocumentAuthorizationService;
use App\Infrastructure\Authorization\ShareAuthorizationService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Override;

class AppServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(
            DocumentAuthorizationServiceInterface::class,
            DocumentAuthorizationService::class
        );

        $this->app->bind(
            ShareAuthorizationServiceInterface::class,
            ShareAuthorizationService::class
        );

        $this->app->bind(
            SessionManagerInterface::class,
            LaravelSessionManager::class
        );

        $this->app->bind(CreateShare::class, fn ($app): CreateShare => new CreateShare(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(ShareAuthorizationServiceInterface::class),
            config('app.frontend_url'),
        ));

        $this->app->bind(GetDocumentShares::class, fn ($app): GetDocumentShares => new GetDocumentShares(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(ShareAuthorizationServiceInterface::class),
            config('app.frontend_url'),
        ));

        $this->app->bind(AccessSharedDocument::class, fn ($app): AccessSharedDocument => new AccessSharedDocument(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(TemplateRepositoryInterface::class),
            config('app.frontend_url'),
        ));

        $this->app->bind(GetSharedDocumentMetadata::class, fn ($app): GetSharedDocumentMetadata => new GetSharedDocumentMetadata(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(TemplateRepositoryInterface::class),
            $app->make(UserRepositoryInterface::class),
        ));

        $this->app->bind(RenderSharedDocument::class, fn ($app): RenderSharedDocument => new RenderSharedDocument(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(TemplateRepositoryInterface::class),
            $app->make(UserRepositoryInterface::class),
            $app->make(RendererFactoryInterface::class),
        ));

        $this->app->bind(SendShareNotification::class, fn ($app): SendShareNotification => new SendShareNotification(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(ClientRepositoryInterface::class),
            $app->make(ShareAuthorizationServiceInterface::class),
            $app->make(ShareNotifierInterface::class),
            config('app.frontend_url'),
        ));

        $this->app->bind(SendShareReminders::class, fn ($app): SendShareReminders => new SendShareReminders(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(UserRepositoryInterface::class),
            $app->make(ShareReminderNotifierInterface::class),
            config('app.frontend_url'),
        ));
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(fn ($user, string $token): string => config('app.frontend_url').'/reset-password?token='.$token.'&email='.urlencode((string) $user->email));
    }
}
