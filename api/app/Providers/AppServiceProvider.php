<?php

namespace App\Providers;

use App\Application\FileGeneration\FileStorageInterface;
use App\Application\Identity\PasswordHasherInterface;
use App\Application\Identity\SessionManagerInterface;
use App\Application\Sharing\AccessSharedDocument;
use App\Application\Sharing\CreateShare;
use App\Application\Sharing\GetDocumentShares;
use App\Domain\Document\DocumentAuthorizationServiceInterface;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\Sharing\ShareAuthorizationServiceInterface;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Template\TemplateRepositoryInterface;
use App\Infrastructure\Auth\LaravelSessionManager;
use App\Infrastructure\Authorization\DocumentAuthorizationService;
use App\Infrastructure\Authorization\ShareAuthorizationService;
use App\Infrastructure\Security\LaravelPasswordHasher;
use App\Infrastructure\Storage\LaravelFileStorage;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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

        $this->app->bind(
            FileStorageInterface::class,
            LaravelFileStorage::class
        );

        $this->app->bind(
            PasswordHasherInterface::class,
            LaravelPasswordHasher::class
        );

        $this->app->bind(CreateShare::class, fn ($app): CreateShare => new CreateShare(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(ShareAuthorizationServiceInterface::class),
            config('app.url'),
        ));

        $this->app->bind(GetDocumentShares::class, fn ($app): GetDocumentShares => new GetDocumentShares(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(ShareAuthorizationServiceInterface::class),
            config('app.url'),
        ));

        $this->app->bind(AccessSharedDocument::class, fn ($app): AccessSharedDocument => new AccessSharedDocument(
            $app->make(ShareRepositoryInterface::class),
            $app->make(DocumentRepositoryInterface::class),
            $app->make(TemplateRepositoryInterface::class),
            config('app.url'),
        ));
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
