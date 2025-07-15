<?php

namespace App\Feather\Roles;

use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RoleManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('feather.modules.roles')) {
            $this->loadRoutes();
            $this->extendUserModel();
        }
    }

    /**
     * Load role routes.
     */
    protected function loadRoutes(): void
    {
        // Routes will be defined here when needed
    }

    /**
     * Extend the User model with role functionality.
     */
    protected function extendUserModel(): void
    {
        // This would extend the Pterodactyl User model
        // with additional role methods in a real implementation
    }
}
