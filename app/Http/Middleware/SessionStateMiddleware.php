<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Modules\Core\Services\SessionState;

class SessionStateMiddleware
{
    /**
     * Gérer la requête entrante et injecter SessionState.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        $sessionState = app(SessionState::class);

        if (!$sessionState->isLoaded()) {
            $sessionState->loadUserSessionData();
        }

        return $next($request);
    }
}
