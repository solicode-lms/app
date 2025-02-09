<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\ContextState;
use Modules\Core\Services\SessionState;
use Modules\Core\Services\ViewState;

class VariablesStateServiceProvider extends ServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ContextState::class);
        $this->app->singleton(SessionState::class, fn($app) => new SessionState());
        $this->app->singleton(ViewState::class, fn($app) => new ViewState());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les états avec toutes les vues
        view()->composer('*', function ($view) {
            $view->with([
                'contextState' => app(ContextState::class),
                'sessionState' => tap(app(SessionState::class), fn($s) => $s->loadUserSessionData()),
                'viewState' => app(ViewState::class)->get(app(ViewState::class)->getViewKey(), [])
            ]);
        });
    }
}
