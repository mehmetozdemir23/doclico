<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\FileGeneration\RendererFactoryInterface;
use App\Application\Identity\PasswordHasherInterface;
use App\Application\Identity\PasswordResetServiceInterface;
use App\Application\Sharing\ShareNotifierInterface;
use App\Application\Sharing\ShareReminderNotifierInterface;
use App\Domain\Client\ClientRepositoryInterface;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\DocumentSequence\DocumentSequenceRepositoryInterface;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\RendererInterface;
use App\Domain\Identity\UserRepositoryInterface;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Template\TemplateRepositoryInterface;
use App\Infrastructure\Auth\LaravelPasswordResetService;
use App\Infrastructure\Persistence\Eloquent\EloquentClientRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDocumentRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentDocumentSequenceRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentShareRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentTemplateRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Infrastructure\Rendering\PdfRenderer;
use App\Infrastructure\Rendering\RendererFactory;
use App\Infrastructure\Security\LaravelPasswordHasher;
use App\Infrastructure\Sharing\LaravelShareNotifier;
use App\Infrastructure\Sharing\LaravelShareReminderNotifier;
use Illuminate\Support\ServiceProvider;
use Override;

final class DomainServiceProvider extends ServiceProvider
{
    public array $singletons = [
        ClientRepositoryInterface::class => EloquentClientRepository::class,
        DocumentSequenceRepositoryInterface::class => EloquentDocumentSequenceRepository::class,
        TemplateRepositoryInterface::class => EloquentTemplateRepository::class,
        DocumentRepositoryInterface::class => EloquentDocumentRepository::class,
        ShareRepositoryInterface::class => EloquentShareRepository::class,
        UserRepositoryInterface::class => EloquentUserRepository::class,
        PasswordHasherInterface::class => LaravelPasswordHasher::class,
        PasswordResetServiceInterface::class => LaravelPasswordResetService::class,
        ShareReminderNotifierInterface::class => LaravelShareReminderNotifier::class,
        ShareNotifierInterface::class => LaravelShareNotifier::class,
    ];

    #[Override]
    public function register(): void
    {
        $this->app->singleton(RendererFactory::class, function (): RendererFactory {
            $factory = new RendererFactory;
            $factory->register(new PdfRenderer);

            return $factory;
        });

        $this->app->bind(RendererFactoryInterface::class, fn ($app) => $app->make(RendererFactory::class));

        $this->app->bind(RendererInterface::class, fn ($app) => $app->make(RendererFactory::class)->make(
            FileFormat::Pdf
        ));
    }
}
