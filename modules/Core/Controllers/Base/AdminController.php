<?php

namespace Modules\Core\Controllers\Base;

/**
 * AdminController est responsable de la gestion des fonctionnalités liées aux administrateurs.
 * Il hérite de AppController, ce qui permet de centraliser les comportements communs.
 */
class AdminController extends AppController
{
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
    }

    /**
     * Surcharge de la méthode callAction.
     * 
     * - Cette méthode est appelée automatiquement pour exécuter une action spécifique dans le contrôleur.
     * - Elle ajoute une logique supplémentaire pour vérifier les autorisations basées sur les permissions utilisateur.
     * 
     * @param string $method Nom de la méthode/action appelée.
     * @param array $parameters Paramètres passés à la méthode.
     * @return mixed Résultat de l'appel de la méthode parente.
     * @throws \Illuminate\Auth\Access\AuthorizationException Si l'utilisateur n'a pas les permissions nécessaires.
     */
    public function callAction($method, $parameters)
    {
        // Récupère l'utilisateur authentifié.
        $user = auth()->user();

        // Récupère le nom du contrôleur sans le namespace.
        $controller = class_basename(get_class($this));

        // Récupère le nom de l'action appelée.
        $action = $method;

        // TODO : à vérifier avec GestionControllersController
        // Formate le nom du contrôleur pour les permissions :
        // - Si le contrôleur s'appelle "GestionControllersController", on enlève "Controller" en utilisant une regex.
        // - Sinon, on remplace "Controller" et "@" par des tirets.
        if ($controller === 'GestionControllersController') {
            $changeName = preg_replace('/Controller$/', '', $controller);
        } else {
            $changeName = str_replace(['Controller', '@'], ['', '-'], $controller);
        }

        // Construit le nom de la permission sous la forme "action-nomDuControleurController".
        $permissions = $action . '-' . $changeName . 'Controller';

        // Vérifie si l'utilisateur a la permission requise.
        $this->authorize($permissions);

        // Appelle la méthode parente pour continuer le traitement.
        return parent::callAction($method, $parameters);
    }
}
