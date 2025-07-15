<?php

namespace App\Feather\Tickets;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TicketServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TicketService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('feather.modules.tickets')) {
            $this->loadRoutes();
        }
    }

    /**
     * Load ticket routes.
     */
    protected function loadRoutes(): void
    {
        // User routes
        Route::middleware(['web', 'auth'])
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

        // Admin routes
        Route::middleware(['web', 'auth', 'admin'])
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
    }
}
