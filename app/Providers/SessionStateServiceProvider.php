<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Modules\Core\Services\SessionState;

class SessionStateServiceProvider extends ServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        // Log::info('SessionStateServiceProvider chargé');
    }

    /**
     * Enregistrer le service.
     */
    public function register(): void
    {
        // Enregistrer SessionState comme singleton pour éviter de recréer l'objet à chaque requête
        $this->app->singleton(SessionState::class, function ($app) {
            return new SessionState();
        });
    }

    /**
     * Initialisation du service.
     */
    public function boot(): void
    {
        // Partager SessionState avec toutes les vues Blade
        view()->composer('*', function ($view) {
            $sessionState = app(SessionState::class);
            $sessionState->loadUserSessionData(); // Charger les données utilisateur
            $view->with('sessionState', $sessionState);
        });
    }
}
