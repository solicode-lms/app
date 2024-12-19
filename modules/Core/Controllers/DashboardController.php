<?php

namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AppController;

/**
 * DashboardController est responsable de la gestion de la logique et des affichages liés au tableau de bord de l'application.
 * 
 * - Hérite de AppController, ce qui permet de centraliser les fonctionnalités communes aux contrôleurs nécessitant une authentification.
 * - Ce contrôleur est généralement utilisé pour des fonctionnalités réservées aux utilisateurs connectés, comme l'accès à un tableau de bord administratif.
 */
class DashboardController extends AppController
{
    /**
     * Affiche la page principale du tableau de bord.
     * 
     * - Charge la vue `dashboard.index`, qui contient l'interface utilisateur du tableau de bord.
     * - Cette méthode peut être étendue pour ajouter des données dynamiques à la vue, comme des statistiques ou des notifications.
     * 
     * @return \Illuminate\View\View La vue du tableau de bord.
     */
    public function index()
    {
        return view('Core::dashboard.index'); // Charge la vue `dashboard/index.blade.php`.
    }

    public function adminlte_lab(){
        return view('Core::dashboard.adminlte-lab');
    }
}
