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
         $contextKey = $allParams["viewState"]["contextKey"] ?? 'default_context';
 
         if (!app()->bound(ViewStateService::class)) {
             app()->singleton(ViewStateService::class, fn() => new ViewStateService($contextKey));
         }
 
         $viewState = app(ViewStateService::class);
 
         if (!empty($allParams["viewState"])) {
             foreach ($allParams["viewState"] as $key => $value) {
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
 
         return $next($request);
     }
 }