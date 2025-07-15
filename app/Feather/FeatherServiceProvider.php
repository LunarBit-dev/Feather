<?php

namespace App\Feather;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Feather\Themes\ThemeManager;
use App\Feather\Shared\FeatherConfig;

class FeatherServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register core Feather services
        $this->app->singleton(ThemeManager::class);
        $this->app->singleton(FeatherConfig::class);

        // Register module service providers
        $this->app->register(\App\Feather\Modpacks\ModpackServiceProvider::class);
        $this->app->register(\App\Feather\Tickets\TicketServiceProvider::class);
        $this->app->register(\App\Feather\Themes\ThemeServiceProvider::class);
        $this->app->register(\App\Feather\Invites\InviteServiceProvider::class);
        $this->app->register(\App\Feather\Roles\RoleServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load Feather configuration
        $this->mergeConfigFrom(
            base_path('app/Feather/config/feather.php'),
            'feather'
        );

        // Load routes
        $this->loadFeatherRoutes();

        // Load views
        $this->loadFeatherViews();

        // Load migrations
        $this->loadMigrationsFrom(base_path('database/migrations/feather'));

        // Load translations
        $this->loadTranslationsFrom(base_path('lang'), 'feather');

        // Publish assets if running in console
        if ($this->app->runningInConsole()) {
            $this->publishes([
                base_path('app/Feather/config/feather.php') => config_path('feather.php'),
            ], 'feather-config');

            $this->publishes([
                base_path('app/Feather/assets') => public_path('feather'),
            ], 'feather-assets');
        }
    }

    /**
     * Load Feather routes.
     */
    protected function loadFeatherRoutes(): void
    {
        Route::middleware(['web'])
            ->prefix('feather')
            ->name('feather.')
            ->group(base_path('routes/feather/web.php'));

        Route::middleware(['api'])
            ->prefix('api/feather')
            ->name('api.feather.')
            ->group(base_path('routes/feather/api.php'));
    }

    /**
     * Load Feather views.
     */
    protected function loadFeatherViews(): void
    {
        View::addLocation(base_path('resources/views/feather'));
        
        // Load theme views if a theme is active
        $themeManager = $this->app->make(ThemeManager::class);
        if ($themeManager->hasActiveTheme()) {
            $themePath = $themeManager->getActiveThemePath();
            if ($themePath && is_dir($themePath . '/views')) {
                View::addLocation($themePath . '/views');
            }
        }
    }
}
