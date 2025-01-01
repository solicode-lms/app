<?php

namespace App\Http\Middleware;

use App\Traits\PageState;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Modules\Core\Services\ContextState;
use Symfony\Component\HttpFoundation\Response;

class ContextStateMiddleware
{
    use PageState;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       
        $contextState =  app(ContextState::class);

        // Charger les variables de contexte depuis la requête
        $contextState->readFromRequest($request);

        $contextState->set("msg","bonjour");

        // // Partager les variables PageState avec toutes les vues Blade
        // View::share('contextState', $contextState->all());


        return $next($request);
    }
}
