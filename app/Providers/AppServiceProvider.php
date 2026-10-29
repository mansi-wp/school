<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // Super-Admin & Admin Bypass: implicitly grants all permissions to super-admin & admin users
        Gate::before(function ($user, $ability) {
            if ($user->user_type === 'admin' || $user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}
