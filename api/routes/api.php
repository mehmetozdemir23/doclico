<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CreateFileGenerationController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\FileGenerationDownloadController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\ShareController;
use App\Http\Controllers\Api\SharedDocumentController;
use App\Http\Controllers\Api\TemplateController;
use Illuminate\Support\Facades\Route;

Route::get('templates', [TemplateController::class, 'index']);
Route::get('templates/{type}', [TemplateController::class, 'show']);
Route::get('share/{token}', SharedDocumentController::class);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('user', [AuthController::class, 'user']);

    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put('settings/password', [SettingsController::class, 'updatePassword']);

    Route::get('documents', [DocumentController::class, 'index']);
    Route::get('documents/{document}', [DocumentController::class, 'show']);
    Route::post('documents', [DocumentController::class, 'store']);
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview']);
    Route::delete('documents/{document}', [DocumentController::class, 'destroy']);

    Route::prefix('documents/{document}/shares')->group(function (): void {
        Route::get('/', [ShareController::class, 'index']);
        Route::post('/', [ShareController::class, 'store']);
        Route::delete('{share}', [ShareController::class, 'destroy']);
    });
});

Route::post('/file-generations', CreateFileGenerationController::class);
Route::get('/file-generations/{fileGeneration}/download', FileGenerationDownloadController::class)->name('file-generations.download');
