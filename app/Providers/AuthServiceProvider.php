<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ImportPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Admin-only gates
        Gate::define('manage-users', fn(User $user) => $user->hasRole('admin'));
        Gate::define('manage-roles', fn(User $user) => $user->hasRole('admin'));
        Gate::define('import-data', fn(User $user) => $user->hasRole('admin'));
        Gate::define('view-import-records', fn(User $user) => $user->hasAnyRole(['admin', 'manager']));

        // Manager + Admin gates
        Gate::define('view-reports', fn(User $user) => $user->hasAnyRole(['admin', 'manager']));
        Gate::define('manage-records', fn(User $user) => $user->hasAnyRole(['admin', 'manager']));

        // All authenticated users
        Gate::define('view-dashboard', fn(User $user) => true);
    }
}
