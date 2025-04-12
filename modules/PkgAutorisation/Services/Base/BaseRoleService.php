<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Modules\PkgAutorisation\Models\Role;
use Modules\Core\Services\BaseService;

/**
 * Classe RoleService pour gérer la persistance de l'entité Role.
 */
class BaseRoleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour roles.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'guard_name'
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
     * Constructeur de la classe RoleService.
     */
    public function __construct()
    {
        parent::__construct(new Role());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutorisation::role.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('role');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de role.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array|object $data)
    {
        return parent::create($data);
    }

    /**
     * Trie par date de mise à jour si il n'existe aucune trie
     * @param mixed $query
     * @param mixed $sort
     */
    public function applySort($query, $sort)
    {
        if ($sort) {
            return parent::applySort($query, $sort);
        }else{
            return $query->orderBy("updated_at","desc");
        }
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getRoleStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
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
            'table' => 'PkgAutorisation::role._table',
            default => 'PkgAutorisation::role._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('role_view_type', $default_view_type);
        $role_viewType = $this->viewState->get('role_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('role_view_type') === 'widgets') {
            $this->viewState->set("filter.role.visible", 1);
        }
        
        // Récupération des données
        $roles_data = $this->paginate($params);
        $roles_stats = $this->getroleStats();
        $roles_filters = $this->getFieldsFilterable();
        $role_instance = $this->createInstance();
        $role_viewTypes = $this->getViewTypes();
        $role_partialViewName = $this->getPartialViewName($role_viewType);
        $role_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.role.stats', $roles_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'role_viewTypes',
            'role_viewType',
            'roles_data',
            'roles_stats',
            'roles_filters',
            'role_instance',
            'role_title',
            'contextKey'
        );
    
        return [
            'roles_data' => $roles_data,
            'roles_stats' => $roles_stats,
            'roles_filters' => $roles_filters,
            'role_instance' => $role_instance,
            'role_viewType' => $role_viewType,
            'role_viewTypes' => $role_viewTypes,
            'role_partialViewName' => $role_partialViewName,
            'contextKey' => $contextKey,
            'role_compact_value' => $compact_value
        ];
    }

}
