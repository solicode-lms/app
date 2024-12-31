<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\PageVariables;

class PageVariablesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
          // Enregistrer PageVariables comme singleton
          $this->app->singleton(PageVariables::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les variables de la page avec toutes les vues
        view()->composer('*', function ($view) {
            $view->with('page', app(PageVariables::class)->all());
        });
    }
}
