<?php

use Illuminate\Support\Facades\Route;
use App\Feather\Themes\Api\ThemeApiController;
use App\Feather\Tickets\Api\TicketApiController;
use App\Feather\Modpacks\Api\ModpackApiController;

/*
|--------------------------------------------------------------------------
| FeatherPanel API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for FeatherPanel modules.
|
*/

// Theme API
Route::middleware(['auth:sanctum'])
    ->prefix('themes')
    ->name('themes.')
    ->group(function () {
        Route::get('/', [ThemeApiController::class, 'index'])->name('index');
        Route::get('/active', [ThemeApiController::class, 'active'])->name('active');
        Route::post('/upload', [ThemeApiController::class, 'upload'])->name('upload');
        Route::post('/{theme}/activate', [ThemeApiController::class, 'activate'])->name('activate');
        Route::delete('/{theme}', [ThemeApiController::class, 'delete'])->name('delete');
    });

// Ticket API
Route::middleware(['auth:sanctum'])
    ->prefix('tickets')
    ->name('tickets.')
    ->group(function () {
        Route::get('/', [TicketApiController::class, 'index'])->name('index');
        Route::post('/', [TicketApiController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketApiController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [TicketApiController::class, 'reply'])->name('reply');
        Route::patch('/{ticket}', [TicketApiController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [TicketApiController::class, 'destroy'])->name('destroy');
    });

// Modpack API
Route::middleware(['auth:sanctum'])
    ->prefix('modpacks')
    ->name('modpacks.')
    ->group(function () {
        Route::get('/', [ModpackApiController::class, 'index'])->name('index');
        Route::get('/browse/{game}', [ModpackApiController::class, 'browse'])->name('browse');
        Route::get('/{modpack}', [ModpackApiController::class, 'show'])->name('show');
        Route::post('/sync', [ModpackApiController::class, 'sync'])->name('sync');
        
        // Server-specific modpack routes
        Route::prefix('servers/{server}')->group(function () {
            Route::get('/installations', [ModpackApiController::class, 'serverInstallations'])->name('server.installations');
            Route::post('/install', [ModpackApiController::class, 'install'])->name('server.install');
            Route::post('/installations/{installation}/rollback', [ModpackApiController::class, 'rollback'])->name('server.rollback');
        });
    });

// Marketplace API (if enabled)
Route::middleware(['auth:sanctum'])
    ->prefix('marketplace')
    ->name('marketplace.')
    ->group(function () {
        Route::get('/plugins', [MarketplaceApiController::class, 'plugins'])->name('plugins');
        Route::get('/themes', [MarketplaceApiController::class, 'themes'])->name('themes');
        Route::get('/modpacks', [MarketplaceApiController::class, 'modpacks'])->name('modpacks');
        Route::post('/install', [MarketplaceApiController::class, 'install'])->name('install');
    });
