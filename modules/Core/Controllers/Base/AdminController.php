<?php

namespace Modules\Core\Controllers\Base;

use App\Http\Middleware\CheckDynamicPermission;
use App\Http\Middleware\ContextStateMiddleware;
use App\Http\Middleware\SessionStateMiddleware;
use App\Http\Middleware\SetViewStateMiddleware;
use Illuminate\Http\Request;
use Modules\Core\App\Helpers\JsonResponseHelper;
use Modules\Core\Services\ContextState;
use Modules\Core\Services\SessionState;
use Modules\Core\Services\UserModelFilterService;
use Modules\Core\Services\ViewStateService;
use Modules\PkgApprenants\App\Requests\VilleRequest;
use Str;
/**
 * AdminController est responsable de la gestion des fonctionnalités liées aux administrateurs.
 * Il hérite de AppController, ce qui permet de centraliser les comportements communs.
 */
class AdminController extends AppController
{
    protected $contextState;
    protected $sessionState;

    protected $viewState ;

    protected $service ;
    
    /**
     * Constructeur du contrôleur.
     * 
     * - Applique le middleware 'auth' pour sécuriser l'accès aux actions de ce contrôleur.
     * - Vous pouvez personnaliser l'application du middleware en utilisant les méthodes `only` ou `except`.
     */
    public function __construct()
    {
        // Middleware globaux
        $this->middleware('auth');
       
        $this->middleware(SessionStateMiddleware::class); // Doit précéder ContextState
        $this->middleware(ContextStateMiddleware::class);
        $this->middleware(SetViewStateMiddleware::class);
        $this->middleware(CheckDynamicPermission::class);

        // Chargement des états partagés
        $this->contextState = app(ContextState::class);
        $this->sessionState = app(SessionState::class);
        $this->viewState = app(ViewStateService::class);

        // Middleware local exécuté avant chaque méthode publique
        $this->middleware(function ($request, $next) {
            $this->shareStates();
            return $next($request);
        });
    }

    /**
     * Partage les états globaux aux vues.
     */
    protected function shareStates(): void
    {
        view()->share([
            'contextState' => $this->contextState,
            'sessionState' => $this->sessionState,
            'viewState' => $this->viewState?->getViewStateData(),
        ]);
    }

    protected function getService() {
        return $this->service;
    }

    public function getData(Request $request)
    {
        $filter = $request->query('filter');
        $value = $request->query('value');
    
        if (!$filter || !$value) {
            return response()->json(['errors' => 'getData : Les paramètres "filter" et "value" sont requis'], 400);
        }
    
        // Récupération des tâches filtrées
        $entities = $this->service->getData($filter, $value);
    
        // Retourner tous les champs avec un champ `toString`
        return response()->json($entities->map(fn($entity) => array_merge(
            $entity->toArray(), // Convertir l'objet en tableau avec tous les champs
            ['toString' => $entity->__toString()] // Ajouter le champ `toString`
        )));
    }


     
    /**
     * Autorisation d'une action dans le current Controller
     * @param string $action
     * @return void
     */
    protected function authorizeAction(string $action)
    {
        $controller = $this->getControllerName(); // Ex: "widgetUtilisateur"
        $permission = $action . '-' . $controller;

        if (!auth()->user()->can($permission)) {
            abort(403, "Permission refusée : $permission");
        }
    }
    protected function getControllerName(): string
    {
        $class = class_basename(static::class); // Ex: WidgetUtilisateurController
        $controllerName = preg_replace('/Controller$/', '', $class); // => WidgetUtilisateur
        return Str::camel($controllerName); // => widgetUtilisateur
    }

    protected function getModelName(): string
    {
        return Str::studly($this->getControllerName()); // Ex: WidgetUtilisateur
    }


 

}
