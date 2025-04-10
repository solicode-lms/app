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
    // protected function readFromRequest(Request $request, ViewStateService $viewState)
    // {
    //     // Fusionner les données de la requête et de la route
    //     $allParams = array_merge($request->all(), $request->route() ? $request->route()->parameters() : []);
    
        
    //     foreach ($allParams as $key => $value) {
    //         if (preg_match('/^(filter|scope)_(.*?)_(.*?)$/', $key, $matches)) {
    //             // Récupérer le préfixe (filter ou scope)
    //             $prefix = $matches[1];
    
    //             // Récupérer le ModelName (entre les underscores)
    //             $modelName = $matches[2];
    
    //             // L'attribut peut être une relation 
    //             // La relation est envoyer par "/" et non par "." car Laravel converti "." vers "_"
    //             // Récupérer et normaliser l'attribut (remplacer "/" par ".")
    //             $attribute = str_replace('/', '.', $matches[3]);

    //             // Construire la nouvelle clé avec des "."
    //             $normalizedKey = "$prefix.$modelName.$attribute";
    
    //             // Enregistrer la valeur corrigée dans ViewState
    //             $viewState->set($normalizedKey, $value);
    //         } 
    //     }
    // }
protected function readFromRequest(Request $request, ViewStateService $viewState)
{

   
    // Fusionner les données de la requête et de la route
    $allParams = array_merge($request->all(), $request->route() ? $request->route()->parameters() : []);

    foreach ($allParams as $key => $value) {
        // Remplacer les underscores par des points pour retrouver la hiérarchie originale
        $normalizedKey = str_replace('_', '.', $key);


        // Diviser la clé en segments
        $segments = explode('.', $normalizedKey);

        // Explication de problème de convertion automatique de "." vers "_" par Laravel
        // La solution : utilisation des attribute qui commencer par une lettre majuscule
        // et convertion vers la forme normale par ce code

        // Vérifier si le premier segment est 'filter' ou 'scope'
        if (in_array($segments[0], ['filter', 'scope'])) {
            // Initialiser les segments normalisés avec le premier niveau (filter ou scope)
            $normalizedSegments = [$segments[0]];
        
            // Supprimer le premier segment de la liste (filter ou scope)
            $remainingSegments = array_slice($segments, 1);
        
            foreach ($remainingSegments as $segment) {
                // Si le segment commence par une majuscule, on démarre un nouveau segment
                if (preg_match('/^[A-Z]/', $segment)) {
                    $normalizedSegments[] = lcfirst($segment);
                } else {
                    // Sinon, on concatène au dernier segment avec un underscore
                    $lastIndex = count($normalizedSegments) - 1;
                    $normalizedSegments[$lastIndex] .= '_' . $segment;
                }
            }
        
            // Reconstituer la clé normalisée
            $finalKey = implode('.', $normalizedSegments);
        
            // Enregistrer la valeur corrigée dans ViewState
            $viewState->set($finalKey, $value);
        }
        
    }
}

    
 }