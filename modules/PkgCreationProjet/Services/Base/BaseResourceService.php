<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCreationProjet\Models\Resource;
use Modules\Core\Services\BaseService;

/**
 * Classe ResourceService pour gérer la persistance de l'entité Resource.
 */
class BaseResourceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour resources.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'lien',
        'description',
        'projet_id'
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
     * Constructeur de la classe ResourceService.
     */
    public function __construct()
    {
        parent::__construct(new Resource());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::resource.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('resource');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre');
        }

    }

    /**
     * Crée une nouvelle instance de resource.
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
    public function getResourceStats(): array
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
                'icon'  => 'fa-table',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgCreationProjet::resource._table',
            default => 'PkgCreationProjet::resource._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('resource_view_type', $default_view_type);
        $resource_viewType = $this->viewState->get('resource_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('resource_view_type') === 'widgets') {
            $this->viewState->set("scope.resource.visible", 1);
        }else{
            $this->viewState->remove("scope.resource.visible");
        }
        
        // Récupération des données
        $resources_data = $this->paginate($params);
        $resources_stats = $this->getresourceStats();
        $resources_filters = $this->getFieldsFilterable();
        $resource_instance = $this->createInstance();
        $resource_viewTypes = $this->getViewTypes();
        $resource_partialViewName = $this->getPartialViewName($resource_viewType);
        $resource_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.resource.stats', $resources_stats);
    
        $resources_permissions = [

            'edit-resource' => Auth::user()->can('edit-resource'),
            'destroy-resource' => Auth::user()->can('destroy-resource'),
            'show-resource' => Auth::user()->can('show-resource'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $resources_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($resources_data as $item) {
                $resources_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'resource_viewTypes',
            'resource_viewType',
            'resources_data',
            'resources_stats',
            'resources_filters',
            'resource_instance',
            'resource_title',
            'contextKey',
            'resources_permissions',
            'resources_permissionsByItem'
        );
    
        return [
            'resources_data' => $resources_data,
            'resources_stats' => $resources_stats,
            'resources_filters' => $resources_filters,
            'resource_instance' => $resource_instance,
            'resource_viewType' => $resource_viewType,
            'resource_viewTypes' => $resource_viewTypes,
            'resource_partialViewName' => $resource_partialViewName,
            'contextKey' => $contextKey,
            'resource_compact_value' => $compact_value,
            'resources_permissions' => $resources_permissions,
            'resources_permissionsByItem' => $resources_permissionsByItem
        ];
    }

}
