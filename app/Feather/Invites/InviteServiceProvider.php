<?php

namespace App\Feather\Invites;

use Illuminate\Support\ServiceProvider;

class InviteServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(InviteManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('feather.modules.invites')) {
            $this->loadRoutes();
        }
    }

    /**
     * Load invite routes.
     */
    protected function loadRoutes(): void
    {
        // Routes for invite system will be defined here
    }
}
