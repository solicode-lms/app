<?php

namespace App\Http\Middleware;

use App\Traits\PageState;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Modules\Core\Services\ContextState;
use Symfony\Component\HttpFoundation\Response;

// Remplir context state depuis request si le contexte est envoyer par GappCrud
// 
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

        // TODO : charger les variable de contexte depuis session
        // il existe des vairables global : comme : annee_formation_id
        // il eiste des variable de contexte : dans le cas d'un model est isOwnedByUser
        // - dans ce cas : formateur_id, apprenant_id, user_id
        // init contextUser in contexteState
        $user = Auth::user(); 
    // Charger le contexte utilisateur si authentifié
        if (Auth::check()) {
            $user = Auth::user();
            foreach ($user->getUsersContext() as $key => $value) {
                
                $contextState->set($key, $value);
            }
        }


        // Charger les variables de contexte depuis la requête
        // $this->readFromRequest($request,$contextState);

        return $next($request);
    }


    /**
     * Lire les valeurs de la requête et de la route avec des préfixes spécifiques,
     * puis les stocker dans le contexte.
     *
     * @param Request $request
     * @param ContextState $contextState
     */
    // protected function readFromRequest(Request $request, ContextState $contextState)
    // {
    //     // Fusionner les données de la requête et de la route
    //     $allParams = array_merge($request->all(), $request->route() ? $request->route()->parameters() : []);
    
    //     // Parcourir tous les paramètres
    //     foreach ($allParams as $key => $value) {
    //         if (str_starts_with($key, 'scope.') || str_starts_with($key, 'global.')) {
    //             $contextState->set($key, $value);
    //         }
    //     }
    // }
    
}
