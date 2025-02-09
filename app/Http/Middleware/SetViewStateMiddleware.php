<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Services\ViewState;
use Illuminate\Support\Facades\Auth;

class SetViewStateMiddleware
{
    /**
     * Manipuler la requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {


        // Fusionner les données de la requête et de la route
        $allParams = array_merge($request->all(), $request->route() ? $request->route()->parameters() : []);
    
        // Définir un nom unique pour chaque vue basée sur la route actuelle
        // $viewKey = $allParams["viewKey"] ? $allParams["viewKey"] : 'default_view';
       
        $viewKey = 'default_view';

        // Enregistrer ViewState comme singleton dans le container Laravel si non existant
        if (!app()->bound(ViewState::class)) {
                app()->singleton(ViewState::class, fn() => new ViewState($viewKey));
        }

        // Récupérer l'instance de ViewState
        $viewState = app(ViewState::class);
                
        // Parcourir tous les paramètres
        foreach ($allParams as $key => $value) {
            if (str_starts_with($key, 'scope.') || str_starts_with($key, 'global.')) {
                $viewState->set($key, $value);
            }
        }

        // Remplir ViewState avec les valeurs issues de la requête
        // $this->readFromRequest($request, $viewState);



        $user = Auth::user(); 
        // Charger le contexte utilisateur si authentifié
        if (Auth::check()) {
                $user = Auth::user();
                foreach ($user->getUsersContext() as $key => $value) {
                    
                    $viewState->set($key, $value);
                }
        }

        return $next($request);
    }

    /**
     * Lire et stocker les variables de contexte depuis la requête dans ViewState.
     *
     * @param Request $request
     * @param ViewState $viewState
     */
    protected function readFromRequest(Request $request, ViewState $viewState)
    {
        
    }


}
