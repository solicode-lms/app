<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgWidgets\Models\WidgetUtilisateur;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetUtilisateurService pour gérer la persistance de l'entité WidgetUtilisateur.
 */
class BaseWidgetUtilisateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetUtilisateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'user_id',
        'widget_id',
        'titre',
        'sous_titre',
        'visible'
    ];

    /**
     * Renvoie les champs de recherche disponibles.
     *
     * @return array
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldsSearchable;
    }

    /**
     * Constructeur de la classe WidgetUtilisateurService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetUtilisateur());
        $this->fieldsFilterable = [];
        $this->title = __('PkgWidgets::widgetUtilisateur.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widgetUtilisateur');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('user_id', $scopeVariables)) {


                    $userService = new \Modules\PkgAutorisation\Services\UserService();
                    $userIds = $this->getAvailableFilterValues('user_id');
                    $users = $userService->getByIds($userIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgAutorisation::user.plural"), 
                        'user_id', 
                        \Modules\PkgAutorisation\Models\User::class, 
                        'name',
                        $users
                    );
                }
            
            
                $sectionWidgetService = new \Modules\PkgWidgets\Services\SectionWidgetService();
                $sectionWidgetIds = $this->getAvailableFilterValues('Widget.Section_widget_id');
                $sectionWidgets = $sectionWidgetService->getByIds($sectionWidgetIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgWidgets::sectionWidget.plural"),
                    'Widget.Section_widget_id', 
                    \Modules\PkgWidgets\Models\SectionWidget::class,
                    "id", 
                    "id",
                    $sectionWidgets
                );
            
            
                if (!array_key_exists('visible', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'visible', 
                        'type'  => 'Boolean', 
                        'label' => 'visible'
                    ];
                }
            



    }


    /**
     * Crée une nouvelle instance de widgetUtilisateur.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getWidgetUtilisateurStats(): array
    {

        $stats = $this->initStats();

        // Ajouter les statistiques du propriétaire
        //$contexteState = $this->getContextState();
        // if ($contexteState !== null) {
        //     $stats[] = $contexteState;
        // }
        

        return $stats;
    }

    public function getContextState()
    {
        $value = $this->viewState->generateTitleFromVariables();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
    }



    /**
     * Retourne les types de vues disponibles pour l'index (ex: table, widgets...)
     */
    public function getViewTypes(): array
    {
        return [
            [
                'type'  => 'table',
                'label' => 'Vue Tableau',
                'icon'  => 'fas fa-table',
            ],
            [
                'type'  => 'widgets',
                'label' => 'Vue Widgets',
                'icon'  => 'fas fa-th-large',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgWidgets::widgetUtilisateur._table',
            'widgets' => 'PkgWidgets::widgetUtilisateur._widgets',
            default => 'PkgWidgets::widgetUtilisateur._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'widgets';
        $this->viewState->setIfEmpty('widgetUtilisateur_view_type', $default_view_type);
        $widgetUtilisateur_viewType = $this->viewState->get('widgetUtilisateur_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('widgetUtilisateur_view_type') === 'widgets') {
            $this->viewState->set("scope.widgetUtilisateur.visible", 1);
        }else{
            $this->viewState->remove("scope.widgetUtilisateur.visible");
        }
        
        // Récupération des données
        $widgetUtilisateurs_data = $this->paginate($params);
        $widgetUtilisateurs_stats = $this->getwidgetUtilisateurStats();
        $widgetUtilisateurs_filters = $this->getFieldsFilterable();
        $widgetUtilisateur_instance = $this->createInstance();
        $widgetUtilisateur_viewTypes = $this->getViewTypes();
        $widgetUtilisateur_partialViewName = $this->getPartialViewName($widgetUtilisateur_viewType);
        $widgetUtilisateur_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.widgetUtilisateur.stats', $widgetUtilisateurs_stats);
    
        $widgetUtilisateurs_permissions = [

            'edit-widgetUtilisateur' => Auth::user()->can('edit-widgetUtilisateur'),
            'destroy-widgetUtilisateur' => Auth::user()->can('destroy-widgetUtilisateur'),
            'show-widgetUtilisateur' => Auth::user()->can('show-widgetUtilisateur'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $widgetUtilisateurs_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($widgetUtilisateurs_data as $item) {
                $widgetUtilisateurs_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'widgetUtilisateur_viewTypes',
            'widgetUtilisateur_viewType',
            'widgetUtilisateurs_data',
            'widgetUtilisateurs_stats',
            'widgetUtilisateurs_filters',
            'widgetUtilisateur_instance',
            'widgetUtilisateur_title',
            'contextKey',
            'widgetUtilisateurs_permissions',
            'widgetUtilisateurs_permissionsByItem'
        );
    
        return [
            'widgetUtilisateurs_data' => $widgetUtilisateurs_data,
            'widgetUtilisateurs_stats' => $widgetUtilisateurs_stats,
            'widgetUtilisateurs_filters' => $widgetUtilisateurs_filters,
            'widgetUtilisateur_instance' => $widgetUtilisateur_instance,
            'widgetUtilisateur_viewType' => $widgetUtilisateur_viewType,
            'widgetUtilisateur_viewTypes' => $widgetUtilisateur_viewTypes,
            'widgetUtilisateur_partialViewName' => $widgetUtilisateur_partialViewName,
            'contextKey' => $contextKey,
            'widgetUtilisateur_compact_value' => $compact_value,
            'widgetUtilisateurs_permissions' => $widgetUtilisateurs_permissions,
            'widgetUtilisateurs_permissionsByItem' => $widgetUtilisateurs_permissionsByItem
        ];
    }

}
