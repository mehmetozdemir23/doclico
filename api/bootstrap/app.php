<?php

use App\Domain\Document\Exception\DocumentDeletionForbiddenException;
use App\Domain\Shared\Exception\EntityNotFoundException;
use App\Domain\Shared\Exception\UnauthorizedException;
use App\Domain\Sharing\Exception\InvalidShareTokenException;
use App\Domain\Sharing\Exception\ShareExpiredException;
use App\Domain\Template\Exception\TemplateTypeNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        });

        $exceptions->render(function (EntityNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        });

        $exceptions->render(function (TemplateTypeNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        });

        $exceptions->render(function (InvalidShareTokenException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        });

        $exceptions->render(function (ShareExpiredException $e) {
            return response()->json(['error' => 'expired', 'message' => 'Ce lien de partage a expiré.'], 410);
        });

        $exceptions->render(function (DocumentDeletionForbiddenException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        });

        $exceptions->render(function (UnauthorizedException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        });
    })->create();
