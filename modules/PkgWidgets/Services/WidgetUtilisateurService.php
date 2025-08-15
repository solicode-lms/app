<?php

namespace Modules\PkgWidgets\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\PkgWidgets\Models\Widget;
use Modules\PkgWidgets\Models\WidgetUtilisateur;
use Modules\PkgWidgets\Services\Base\BaseWidgetUtilisateurService;

/**
 * Service de gestion des widgets utilisateur.
 */
class WidgetUtilisateurService extends BaseWidgetUtilisateurService
{

     protected array $index_with_relations = [
        'widget',
        'widget.operation',
        'widget.model',
        'widget.type',
    ];


    /**
     * Pré-remplit les données lors de la création d'un widget utilisateur.
     */
    public function dataCalcul($data)
    {
        $widgetUtilisateur = parent::dataCalcul($data);

        if (!isset($widgetUtilisateur->id)) {
            $widgetUtilisateur->titre = $widgetUtilisateur->widget->name;
            $widgetUtilisateur->sous_titre = $widgetUtilisateur->widget->label;
        }

        return $widgetUtilisateur;
    }

    /**
     * Retourne les widgets visibles de l'utilisateur connecté,
     * en créant ceux manquants à partir de ses rôles.
     */
    public function getWidgetUtilisateurOfCurrentUser()
    {
        $user = Auth::user();
        $this->syncWidgetsFromRoles($user->id);

        return $this->model
            ->where('user_id', $user->id)
            ->where('visible', true)
            ->get();
    }

    /**
     * Crée une nouvelle instance de WidgetUtilisateur avec des valeurs par défaut.
     */
    public function createInstance(array $data = [])
    {
        $item = parent::createInstance($data);
        $item->visible = true;
        $item->ordre = 1;
        return $item;
    }

    /**
     * Paginer les widgets utilisateur de l'utilisateur connecté,
     * après avoir synchronisé ceux liés à ses rôles.
     */
    public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
        $user = Auth::user();
    
        // Instancier le service une seule fois
        $widgetService = new WidgetService();
    
        // Synchroniser les widgets de l’utilisateur
        $this->syncWidgetsFromRoles($user->id);
    
        // Paginer normalement
        $results = parent::paginate($params, $perPage, $columns);
    
        // Appliquer executeWidget sur chaque widgetUtilisateur
        $results->getCollection()->transform(function ($widgetUtilisateur) use ($widgetService) {
            if ($widgetUtilisateur->widget) {
                $widgetUtilisateur->widget = $widgetService->executeWidget(
                    $widgetUtilisateur->widget,
                    $widgetUtilisateur
                );
            }
            return $widgetUtilisateur;
        });
    
        return $results;
    }
    
    

    /**
     * Synchronise les widgets utilisateur à partir des rôles de l'utilisateur.
     */
    protected function syncWidgetsFromRoles(int $userId): void
    {
        $user = Auth::user();

        $widgets = Widget::whereHas('roles', function ($query) use ($user) {
            $query->whereIn('roles.id', $user->roles->pluck('id'));
        })->get();

        foreach ($widgets as $widget) {
            WidgetUtilisateur::firstOrCreate(
                [
                    'user_id' => $userId,
                    'widget_id' => $widget->id,
                ],
                [
                    'visible' => true,
                    'ordre' => $widget->ordre,
                    'titre' => $widget->name,
                    'sous_titre' => $widget->label,
                ]
            );
        }
    }

    /**
     * Supprime tous les WidgetUtilisateur liés à un Widget donné.
     *
     * @param int $widgetId
     * @return int Nombre d'éléments supprimés
     */
    public function deleteAllWidgetUtilisateursByWidgetId(int $widgetId): int
    {
        return WidgetUtilisateur::where('widget_id', $widgetId)->delete();
    }

    /**
     * Supprime tous les WidgetUtilisateur d'un widget
     * dont l'utilisateur n'a pas un des rôles autorisés.
     *
     * @param int   $widgetId
     * @param array $roleIds  Liste des rôles autorisés pour ce widget
     * @return int  Nombre d'éléments supprimés
     */
    public function deleteWidgetUtilisateursNotInRoles(int $widgetId, array $roleIds): int
    {
        if (empty($roleIds)) {
            // Aucun rôle défini → suppression de tous les widgets utilisateurs liés
            return WidgetUtilisateur::where('widget_id', $widgetId)->delete();
        }

        return WidgetUtilisateur::where('widget_id', $widgetId)
            ->whereDoesntHave('user.roles', function ($query) use ($roleIds) {
                $query->whereIn('roles.id', $roleIds);
            })
            ->delete();
    }
}
