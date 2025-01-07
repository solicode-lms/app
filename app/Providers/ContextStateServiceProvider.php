<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\ContextState;
use Illuminate\Support\Facades\Log;

class ContextStateServiceProvider extends ServiceProvider
{

    public function __construct($app)
    {
        parent::__construct($app);
        Log::info('ContextStateServiceProvider chargé');
        // dd("ContextStatProvider");
    }

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
           
            $view->with('contextState', app(ContextState::class));
        });
    }
}
