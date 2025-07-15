<?php

namespace App\Feather\Modpacks;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ModpackServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModpackManager::class);
        $this->app->singleton(ModpackInstaller::class);
        
        // Register game adapters
        $this->app->bind('modpack.adapter.minecraft', MinecraftAdapter::class);
        $this->app->bind('modpack.adapter.valheim', ValheimAdapter::class);
        $this->app->bind('modpack.adapter.rust', RustAdapter::class);
        $this->app->bind('modpack.adapter.ark', ArkAdapter::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('feather.modules.modpacks')) {
            $this->loadRoutes();
        }
    }

    /**
     * Load modpack routes.
     */
    protected function loadRoutes(): void
    {
        // User routes
        Route::middleware(['web', 'auth'])
            ->prefix('modpacks')
            ->name('modpacks.')
            ->group(function () {
                Route::get('/', [ModpackController::class, 'index'])->name('index');
                Route::get('/browse', [ModpackController::class, 'browse'])->name('browse');
                Route::get('/browse/{game}', [ModpackController::class, 'browseGame'])->name('browse.game');
                Route::get('/{modpack}', [ModpackController::class, 'show'])->name('show');
                Route::post('/{modpack}/install', [ModpackController::class, 'install'])->name('install');
            });

        // Server-specific routes
        Route::middleware(['web', 'auth', 'server'])
            ->prefix('servers/{server}/modpacks')
            ->name('servers.modpacks.')
            ->group(function () {
                Route::get('/', [ServerModpackController::class, 'index'])->name('index');
                Route::get('/install', [ServerModpackController::class, 'install'])->name('install');
                Route::post('/install', [ServerModpackController::class, 'processInstall'])->name('process-install');
                Route::get('/history', [ServerModpackController::class, 'history'])->name('history');
                Route::post('/{installation}/rollback', [ServerModpackController::class, 'rollback'])->name('rollback');
            });

        // Admin routes
        Route::middleware(['web', 'auth', 'admin'])
            ->prefix('admin/modpacks')
            ->name('admin.modpacks.')
            ->group(function () {
                Route::get('/', [AdminModpackController::class, 'index'])->name('index');
                Route::get('/sync', [AdminModpackController::class, 'sync'])->name('sync');
                Route::post('/sync', [AdminModpackController::class, 'procesSync'])->name('process-sync');
                Route::get('/settings', [AdminModpackController::class, 'settings'])->name('settings');
                Route::post('/settings', [AdminModpackController::class, 'updateSettings'])->name('update-settings');
            });
    }
}
