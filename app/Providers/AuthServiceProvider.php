<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Student;
use App\Models\FeePayment;
use App\Models\SystemMenu;
use App\Policies\StudentPolicy;
use App\Policies\FeePolicy;
use App\Policies\MenuPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Student::class => StudentPolicy::class,
        FeePayment::class => FeePolicy::class,
        SystemMenu::class => MenuPolicy::class,
    ];

    public function boot(): void
    {
        // Implicitly grant 'super-admin' role and admin user_type all permission checks
        Gate::before(function ($user, $ability) {
            return ($user->user_type === 'admin' || $user->hasRole('super-admin')) ? true : null;
        });
    }
}
