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
        $this->middleware('auth'); // Applique 'auth' à toutes les méthodes.
        // Exemple : Appliquer uniquement à certaines méthodes
        // $this->middleware('auth')->only(['index', 'store']);
        // Exemple : Exclure certaines méthodes
        // $this->middleware('auth')->except(['help']);

        // Middleware appliqué à toutes les méthodes
        $this->middleware(CheckDynamicPermission::class);

        // SessionState doit être charger avant ContextState
        $this->middleware(SessionStateMiddleware::class);

        // Middleware appliqué à toutes les méthodes
        $this->middleware(ContextStateMiddleware::class);

        $this->middleware(SetViewStateMiddleware::class);


        // Scrop management
        $this->contextState = app(ContextState::class);
        $this->sessionState = app(SessionState::class);
        $this->viewState = app(ViewStateService::class);
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
        $taches = $this->service->getData($filter, $value);
    
        // Retourner tous les champs avec un champ `toString`
        return response()->json($taches->map(fn($tache) => array_merge(
            $tache->toArray(), // Convertir l'objet en tableau avec tous les champs
            ['toString' => $tache->__toString()] // Ajouter le champ `toString`
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
