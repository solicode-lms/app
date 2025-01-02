<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\ContextState;

class ContextStateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
          // Enregistrer PageVariables comme singleton
          $this->app->singleton(ContextState::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les variables de la page avec toutes les vues
        view()->composer('*', function ($view) {
            dd("ContextStatProvider");
            $view->with('contextState', app(ContextState::class)->all());
        });
    }
}
