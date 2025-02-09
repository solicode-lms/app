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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager les états avec toutes les vues
        view()->composer('*', function ($view) {
            $viewData = [];

            if (app()->bound(ViewState::class)) {
                $viewState = app(ViewState::class);
                $viewData['viewState'] = $viewState->getViewData();
            }

            $view->with(array_merge($viewData, [
                'contextState' => app(ContextState::class),
                'sessionState' => tap(app(SessionState::class), fn($s) => $s->loadUserSessionData()),
            ]));
        });
    }
}