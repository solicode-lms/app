<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ViewState;

class SetViewStateMiddleware
{
    protected ViewState $viewState;

    public function __construct(ViewState $viewState)
    {
        $this->viewState = $viewState;
    }

    /**
     * Manipuler la requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Définir un nom unique pour chaque vue basée sur la route actuelle
        $viewKey = str_replace(['/', '-'], '_', $request->route()->getName());
        $this->viewState->setViewKey($viewKey);

        return $next($request);
    }
}
