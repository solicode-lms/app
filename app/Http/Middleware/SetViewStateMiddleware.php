<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Core\Services\ViewStateService;
use Illuminate\Support\Facades\Auth;

/**
 * `SetViewStateMiddleware` initialise et gère le `ViewStateService` pour chaque requête HTTP.  
 * Il permet une gestion dynamique des contextes (`contextKey`) et des variables d'état entre le frontend et le backend.
 *
 * ## Règles de gestion :
 * 1. **Gestion du `contextKey`**  
 *    - Chaque requête est associée à un `contextKey` unique.  
 *    - Si absent, un `contextKey` par défaut (`default_context`) est utilisé.
 *
 * 2. **Transmission des données de `viewState`**  
 *    - `viewState` est transmis comme un objet `viewState[]` pour gérer plusieurs contextes simultanément.  
 *    - Les variables de `viewState` sont envoyées au serveur et mises à jour dynamiquement côté frontend.
 *
 * 3. **Séparation et Isolation des Contextes**  
 *    - Plusieurs `contextKey` peuvent exister simultanément dans une `Gapp Page`.
 *    - Chaque requête HTTP met à jour uniquement son `contextKey` sans affecter les autres.
 *
 * 4. **Sécurité et Consistance**  
 *    - L'utilisateur authentifié voit ses variables contextuelles ajoutées automatiquement.
 *    - Assure l'intégrité des données entre les différentes requêtes AJAX.
 */

 class SetViewStateMiddleware
 {
     public function handle(Request $request, Closure $next)
     {
         $allParams = array_merge($request->all(), $request->route()?->parameters() ?? []);
        
        // Récupérer viewState et contextKey depuis les paramètres
        $viewStateParams = $allParams["viewState"] ?? '{}';
        $viewStateParams = is_string($viewStateParams) ? json_decode($viewStateParams, true) : $viewStateParams;
        
        $contextKey = $viewStateParams["contextKey"] ?? 'default_context';
        $viewStateData = $viewStateParams["viewState"] ?? [];
        
         if (!app()->bound(ViewStateService::class)) {
             app()->singleton(ViewStateService::class, fn() => new ViewStateService($contextKey));
         }
 
         $viewState = app(ViewStateService::class);
 
         if (!empty($viewStateData)) {
             foreach ($viewStateData as $key => $value) {
                 if ($key !== 'contextKey') {
                     $viewState->set($key, $value);
                 }
             }
         }
 
         if (Auth::check()) {
             foreach (Auth::user()->getUsersContext() as $key => $value) {
                 $viewState->set($key, $value);
             }
         }

        // Charger les variables de contexte depuis la requête
        $this->readFromRequest($request,$viewState);

 
         return $next($request);
     }

     /**
     * Lire les valeurs de la requête et de la route avec des préfixes spécifiques,
     * puis les stocker dans le contexte.
     *
     * @param Request $request
     * @param ContextState $contextState
     */
    protected function readFromRequest(Request $request, ViewStateService $viewState)
    {
        // Fusionner les données de la requête et de la route
        $allParams = array_merge($request->all(), $request->route() ? $request->route()->parameters() : []);
    
        foreach ($allParams as $key => $value) {
            if (preg_match('/^(filter|scope)_(.*?)_(.*?)$/', $key, $matches)) {
                // Récupérer le préfixe (filter ou scope)
                $prefix = $matches[1];
    
                // Récupérer le ModelName (entre les underscores)
                $modelName = $matches[2];
    
                // Récupérer le reste de l'attribut
                $attribute = $matches[3];
    
                // Construire la nouvelle clé avec des "."
                $normalizedKey = "$prefix.$modelName.$attribute";
    
                // Enregistrer la valeur corrigée dans ViewState
                $viewState->set($normalizedKey, $value);
            } 
        }
    }
    
 }