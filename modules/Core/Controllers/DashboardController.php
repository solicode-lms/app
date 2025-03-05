<?php

namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AppController;
use Modules\PkgWidgets\Models\Widget;
use Modules\PkgWidgets\Services\WidgetService;
use Modules\PkgWidgets\Services\WidgetUtilisateurService;

/**
 * DashboardController est responsable de la gestion de la logique et des affichages liés au tableau de bord de l'application.
 * 
 * - Hérite de AppController, ce qui permet de centraliser les fonctionnalités communes aux contrôleurs nécessitant une authentification.
 * - Ce contrôleur est généralement utilisé pour des fonctionnalités réservées aux utilisateurs connectés, comme l'accès à un tableau de bord administratif.
 */
class DashboardController extends AppController
{
    protected $widgetService;

        /**
     * Injecter le service dans le contrôleur.
     *
     * @param WidgetService $widgetService
     */
    public function __construct(WidgetService $widgetService)
    {
        $this->widgetService = $widgetService;
    }
 

    /**
     * Afficher le tableau de bord avec les widgets.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $widgetUtilisateurService = new WidgetUtilisateurService();

        $widgetUitlisateurs = $widgetUtilisateurService->getWidgetUtilisateurOfCurrentUser();
        // Charger tous les widgets configurés avec leurs relations
        // $widgets = Widget::with(['type', 'model', 'operation'])->get();

        $widgets = []; // Initialisation d'un tableau pour stocker les widgets

        // Exécuter la requête de chaque widget et récupérer les données
        foreach ($widgetUitlisateurs as $widgetUtilisateur) {
            try {
               
                $widget = $this->widgetService->executeWidget($widgetUtilisateur->widget);
              
            } catch (\Exception $e) {
                // Si une erreur survient, capturer l'exception
                $widget->error = $e->getMessage();

            }

                // Ajouter le widget à la collection
    $widgets[] = $widget;
        }

        // Retourner la vue avec les widgets
       
        return view('Core::dashboard.index', compact('widgets')); // Charge la vue `dashboard/index.blade.php`.
    }


    public function adminlte_lab(){
        return view('Core::dashboard.adminlte-lab');
    }
}
