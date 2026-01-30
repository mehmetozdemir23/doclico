<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use App\Application\FileGeneration\FileStorageInterface;
use App\Application\Identity\PasswordHasherInterface;
use App\Domain\Document\DocumentRepositoryInterface;
use App\Domain\FileGeneration\FileFormat;
use App\Domain\FileGeneration\FileGenerationRepositoryInterface;
use App\Domain\FileGeneration\RendererInterface;
use App\Domain\Identity\UserRepositoryInterface;
use App\Domain\Sharing\ShareRepositoryInterface;
use App\Domain\Template\TemplateRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\EloquentDocumentRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentFileGenerationRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentShareRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentTemplateRepository;
use App\Infrastructure\Persistence\Eloquent\EloquentUserRepository;
use App\Infrastructure\Rendering\PdfRenderer;
use App\Infrastructure\Rendering\RendererFactory;
use App\Infrastructure\Security\LaravelPasswordHasher;
use App\Infrastructure\Storage\LocalFileStorage;
use Illuminate\Support\ServiceProvider;

final class DomainServiceProvider extends ServiceProvider
{
    public array $singletons = [
        // Repositories
        TemplateRepositoryInterface::class => EloquentTemplateRepository::class,
        FileGenerationRepositoryInterface::class => EloquentFileGenerationRepository::class,
        DocumentRepositoryInterface::class => EloquentDocumentRepository::class,
        ShareRepositoryInterface::class => EloquentShareRepository::class,
        UserRepositoryInterface::class => EloquentUserRepository::class,

        // Infrastructure
        FileStorageInterface::class => LocalFileStorage::class,
        PasswordHasherInterface::class => LaravelPasswordHasher::class,
    ];

    public function register(): void
    {
        $this->app->singleton(RendererFactory::class, function (): RendererFactory {
            $factory = new RendererFactory;
            $factory->register(new PdfRenderer);

            return $factory;
        });

        $this->app->bind(RendererInterface::class, fn ($app) => $app->make(RendererFactory::class)->make(
            FileFormat::Pdf
        ));
    }
}
