<?php

namespace App\Providers;

use App\Policies\AppreciationPolicy;
use App\Policies\GenericPolicy;
use Modules\PkgCompetences\Models\Appreciation;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Appreciation::class => GenericPolicy::class, // Mapper Appreciation avec GenericPolicy
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Appliquer la Policy Générique à tous les modèles
       // Gate::policy('*', GenericPolicy::class);


        // Bypass des permissions pour le rôle Super Admin
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('root')) {
                return true; // Autorise toutes les actions
            }
        });
    }
}
