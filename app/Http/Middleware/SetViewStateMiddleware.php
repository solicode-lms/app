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

        // Charger les variables de contexte depuis la requête qui commence par filter ou scope
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

    /**
     * Lecture et normalisation des paramètres "filter" et "scope" depuis la requête
     *
     * Laravel convertit automatiquement les clés comme "filter.Model.Attr1.Attr2"
     * en "filter_model_attr1_attr2", ce qui rend leur interprétation complexe.
     * Cette méthode restaure la hiérarchie d'origine en utilisant une convention :
     * - Chaque nouveau niveau est marqué par une majuscule en début de segment
     * - Le dernier segment (attribut réel) reste en snake_case
     *
     * @param \Illuminate\Http\Request $request
     * @param \Modules\Core\Services\ViewStateService $viewState
     * @return void
     */
    protected function readFromRequest(Request $request, ViewStateService $viewState)
    {
        // Fusionner les paramètres de la requête et de la route
        $allParams = array_merge(
            $request->all(),
            $request->route() ? $request->route()->parameters() : []
        );
    
        foreach ($allParams as $key => $value) {


            // // Read Sort Vairable 
            // if($key == "sort"){
            //     $viewState->set($key, $value);
            //     continue;
            // }



            // Remplacer les underscores par des points pour simuler l’arborescence
            $normalizedKey = str_replace('_', '.', $key);
            $segments = explode('.', $normalizedKey);
    
            // On ne traite que les clés commençant par "filter" ou "scope"
            if (!preg_match('/^(filter|scope|dataSource|sort)(\.[a-zA-Z0-9_]+)+$/', $normalizedKey)) {
                continue;
            }
    
            // Initialiser avec les deux premiers segments (ex: filter, RealisationTache)
            $normalizedSegments = [$segments[0], $segments[1], $segments[2]];
    
            // Analyse à partir du 3e index (4e segment)
            foreach (array_slice($segments, 3) as $i => $segment) {
                $absoluteIndex = $i + 2;
    
                if (preg_match('/^[A-Z]/', $segment)) {
                    // Nouveau niveau hiérarchique
                    $normalizedSegments[] = $segment;
                } else {
                    // Ajouter comme suffixe à l’élément précédent (avec _)
                    $lastIndex = count($normalizedSegments) - 1;
                    $normalizedSegments[$lastIndex] .= '_' . $segment;
                }
            }
    
            // Clé finale sous forme hiérarchique
            $finalKey = implode('.', $normalizedSegments);
    
            // Enregistrement dans ViewState
            $viewState->set($finalKey, $value);
        }
    }
    
    
 }