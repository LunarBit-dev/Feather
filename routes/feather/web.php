<?php

use Illuminate\Support\Facades\Route;
use App\Feather\Themes\ThemeController;
use App\Feather\Tickets\TicketController;
use App\Feather\Tickets\AdminTicketController;
use App\Feather\Modpacks\ModpackController;
use App\Feather\Modpacks\ServerModpackController;
use App\Feather\Modpacks\AdminModpackController;

/*
|--------------------------------------------------------------------------
| FeatherPanel Web Routes
|--------------------------------------------------------------------------
|
| Here are the web routes for FeatherPanel modules.
|
*/

// Theme Management (Admin only)
Route::middleware(['auth', 'admin'])
    ->prefix('admin/themes')
    ->name('admin.themes.')
    ->group(function () {
        Route::get('/', [ThemeController::class, 'index'])->name('index');
        Route::post('/upload', [ThemeController::class, 'upload'])->name('upload');
        Route::post('/{theme}/activate', [ThemeController::class, 'activate'])->name('activate');
        Route::delete('/{theme}', [ThemeController::class, 'delete'])->name('delete');
    });

// Ticket System
Route::middleware(['auth'])
    ->prefix('tickets')
    ->name('tickets.')
    ->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/close', [TicketController::class, 'close'])->name('close');
        Route::post('/{ticket}/reopen', [TicketController::class, 'reopen'])->name('reopen');
    });

// Admin Ticket Management
Route::middleware(['auth', 'admin'])
    ->prefix('admin/tickets')
    ->name('admin.tickets.')
    ->group(function () {
        Route::get('/', [AdminTicketController::class, 'index'])->name('index');
        Route::get('/{ticket}', [AdminTicketController::class, 'show'])->name('show');
        Route::post('/{ticket}/reply', [AdminTicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/assign', [AdminTicketController::class, 'assign'])->name('assign');
        Route::post('/{ticket}/priority', [AdminTicketController::class, 'setPriority'])->name('priority');
        Route::post('/{ticket}/department', [AdminTicketController::class, 'setDepartment'])->name('department');
        Route::post('/{ticket}/close', [AdminTicketController::class, 'close'])->name('close');
        Route::delete('/{ticket}', [AdminTicketController::class, 'destroy'])->name('destroy');
    });

// Modpack Browser
Route::middleware(['auth'])
    ->prefix('modpacks')
    ->name('modpacks.')
    ->group(function () {
        Route::get('/', [ModpackController::class, 'index'])->name('index');
        Route::get('/browse', [ModpackController::class, 'browse'])->name('browse');
        Route::get('/browse/{game}', [ModpackController::class, 'browseGame'])->name('browse.game');
        Route::get('/{modpack}', [ModpackController::class, 'show'])->name('show');
    });

// Server Modpack Management
Route::middleware(['auth', 'server'])
    ->prefix('servers/{server}/modpacks')
    ->name('servers.modpacks.')
    ->group(function () {
        Route::get('/', [ServerModpackController::class, 'index'])->name('index');
        Route::get('/install', [ServerModpackController::class, 'install'])->name('install');
        Route::post('/install', [ServerModpackController::class, 'processInstall'])->name('process-install');
        Route::get('/history', [ServerModpackController::class, 'history'])->name('history');
        Route::post('/{installation}/rollback', [ServerModpackController::class, 'rollback'])->name('rollback');
    });

// Admin Modpack Management
Route::middleware(['auth', 'admin'])
    ->prefix('admin/modpacks')
    ->name('admin.modpacks.')
    ->group(function () {
        Route::get('/', [AdminModpackController::class, 'index'])->name('index');
        Route::get('/sync', [AdminModpackController::class, 'sync'])->name('sync');
        Route::post('/sync', [AdminModpackController::class, 'processSync'])->name('process-sync');
        Route::get('/settings', [AdminModpackController::class, 'settings'])->name('settings');
        Route::post('/settings', [AdminModpackController::class, 'updateSettings'])->name('update-settings');
    });
