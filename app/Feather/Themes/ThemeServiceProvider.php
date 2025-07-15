<?php

namespace App\Feather\Themes;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ThemeManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('feather.modules.themes')) {
            $this->loadRoutes();
        }
    }

    /**
     * Load theme routes.
     */
    protected function loadRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('admin/themes')
            ->name('admin.themes.')
            ->group(function () {
                Route::get('/', [ThemeController::class, 'index'])->name('index');
                Route::post('/upload', [ThemeController::class, 'upload'])->name('upload');
                Route::post('/{theme}/activate', [ThemeController::class, 'activate'])->name('activate');
                Route::delete('/{theme}', [ThemeController::class, 'delete'])->name('delete');
            });
    }
}
