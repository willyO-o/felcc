<?php

namespace App\Providers;

// use Illuminate\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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

        Gate::define('acceso-total', function (User $user) {
            return in_array($user->role->nombre, ['superadmin', 'administrador','tecnico']);
        });
        //
        // Esta regla "mágica" sirve para cualquier @can o @canany
        Gate::before(function (User $user, string $ability) {
            // Si el rol del usuario coincide con el nombre de la habilidad solicitada

            // dd($user->role->nombre, $ability);
            if ($user->role->nombre == $ability) {
                return true;
            }
        });
    }
}
