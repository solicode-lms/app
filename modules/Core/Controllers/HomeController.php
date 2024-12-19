<?php

namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\PublicController;

/**
 * HomeController est responsable de gérer la logique pour la page d'accueil de l'application.
 * 
 * - Hérite de PublicController, ce qui signifie qu'il est conçu pour des fonctionnalités publiques 
 *   accessibles sans authentification (sauf si un middleware est explicitement ajouté).
 * - Ce contrôleur contient des actions liées à l'affichage de la page d'accueil ou d'autres pages publiques.
 */
class HomeController extends PublicController
{
    /**
     * Affiche la page d'accueil de l'application.
     * 
     * - Charge la vue `home`, qui est généralement utilisée pour afficher la page d'accueil.
     * - Cette méthode peut être étendue pour ajouter des données dynamiques à la vue.
     * 
     * @return \Illuminate\View\View La vue de la page d'accueil.
     */
    public function index()
    {
        return view('Core::home.index'); // Charge la vue `home` située dans le dossier des vues.
    }
}
