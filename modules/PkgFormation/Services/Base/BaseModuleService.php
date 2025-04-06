<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Modules\PkgFormation\Models\Module;
use Modules\Core\Services\BaseService;

/**
 * Classe ModuleService pour gérer la persistance de l'entité Module.
 */
class BaseModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour modules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
        'masse_horaire',
        'filiere_id'
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
     * Constructeur de la classe ModuleService.
     */
    public function __construct()
    {
        parent::__construct(new Module());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('module');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('filiere_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::filiere.plural"), 'filiere_id', \Modules\PkgFormation\Models\Filiere::class, 'code');
        }
    }

    /**
     * Crée une nouvelle instance de module.
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
    public function getModuleStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Filiere::class,
                'modules',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

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
            'table' => 'PkgFormation::module._table',
            default => 'PkgFormation::module._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('module_view_type', $default_view_type);
        $module_viewType = $this->viewState->get('module_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('module_view_type') === 'widgets') {
            $this->viewState->set("filter.module.visible", 1);
        }
        
        // Récupération des données
        $modules_data = $this->paginate($params);
        $modules_stats = $this->getmoduleStats();
        $modules_filters = $this->getFieldsFilterable();
        $module_instance = $this->createInstance();
        $module_viewTypes = $this->getViewTypes();
        $module_partialViewName = $this->getPartialViewName($module_viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.module.stats', $modules_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'module_viewTypes',
            'module_viewType',
            'modules_data',
            'modules_stats',
            'modules_filters',
            'module_instance'
        );
    
        return [
            'modules_data' => $modules_data,
            'modules_stats' => $modules_stats,
            'modules_filters' => $modules_filters,
            'module_instance' => $module_instance,
            'module_viewType' => $module_viewType,
            'module_viewTypes' => $module_viewTypes,
            'module_partialViewName' => $module_partialViewName,
            'module_compact_value' => $compact_value
        ];
    }

}
