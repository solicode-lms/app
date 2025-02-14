<?php

namespace Modules\Core\App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Services\ContextState;
use Modules\Core\Services\SessionState;
use Modules\Core\Services\ViewStateService;

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

            if (app()->bound(ViewStateService::class)) {
                $viewState = app(ViewStateService::class);
                $viewData['viewState'] = $viewState->getViewStateData();
            }

            $view->with(array_merge($viewData, [
                'contextState' => app(ContextState::class),
                'sessionState' => tap(app(SessionState::class), fn($s) => $s->loadUserSessionData()),
            ]));
        });
    }
}