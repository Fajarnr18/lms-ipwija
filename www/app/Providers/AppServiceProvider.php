<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('admin-access', function ($user) {
            return $user->role === 'admin' && $user->is_active;
        });

        Gate::define('mahasiswa-access', function ($user) {
            return $user->role === 'mahasiswa' && $user->is_active;
        });

        Gate::define('dosen-access', function ($user) {
            return $user->role === 'dosen' && $user->is_active;
        });
    }
}
