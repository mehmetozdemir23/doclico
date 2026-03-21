<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ShareController;
use App\Http\Controllers\Api\TemplateController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────

Route::get('share/{token}/info', [ShareController::class, 'info'])->middleware('throttle:60,1');
Route::get('share/{token}', [ShareController::class, 'download'])->middleware('throttle:60,1');

Route::get('auth/google/redirect', [GoogleAuthController::class, 'redirect']);
Route::get('auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');

// ── Authentifié ───────────────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function (): void {

    // Auth
    Route::get('user', [AuthController::class, 'user']);
    Route::delete('user', [ProfileController::class, 'destroy'])->middleware('throttle:3,60');

    // Profil
    Route::get('profile', [ProfileController::class, 'show']);
    Route::patch('profile', [ProfileController::class, 'update']);
    Route::put('profile/password', [ProfileController::class, 'updatePassword']);
    Route::post('profile/logo', [ProfileController::class, 'storeLogo']);
    Route::delete('profile/logo', [ProfileController::class, 'destroyLogo']);
    Route::get('profile/export', [ProfileController::class, 'export']);

    // Clients
    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::put('clients/{client}', [ClientController::class, 'update']);
    Route::delete('clients/{client}', [ClientController::class, 'destroy']);

    // Templates
    Route::get('templates', [TemplateController::class, 'index']);
    Route::get('templates/{type}', [TemplateController::class, 'show']);

    // Documents
    Route::get('documents', [DocumentController::class, 'index']);
    Route::post('documents', [DocumentController::class, 'store']);
    Route::get('documents/{document}/preview', [DocumentController::class, 'preview']);
    Route::get('documents/{document}/download', [DocumentController::class, 'download']);
    Route::delete('documents/{document}', [DocumentController::class, 'destroy']);

    // Partage
    Route::get('documents/{document}/shares', [ShareController::class, 'index']);
    Route::post('documents/{document}/shares', [ShareController::class, 'store']);
    Route::post('shares/{share}/notify', [ShareController::class, 'notify']);
    Route::delete('shares/{share}', [ShareController::class, 'destroy']);
});
