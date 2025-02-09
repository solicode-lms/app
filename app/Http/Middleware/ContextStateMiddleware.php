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
        $contextState->setUserContexe( $user->getUsersContext());
 
        // Charger les variables de contexte depuis la requête
        $contextState->readFromRequest($request);

        return $next($request);
    }
}
