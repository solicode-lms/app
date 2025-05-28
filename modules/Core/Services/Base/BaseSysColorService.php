<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\SysColor;
use Modules\Core\Services\BaseService;

/**
 * Classe SysColorService pour gérer la persistance de l'entité SysColor.
 */
class BaseSysColorService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysColors.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'hex'
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
     * Constructeur de la classe SysColorService.
     */
    public function __construct()
    {
        parent::__construct(new SysColor());
        $this->fieldsFilterable = [];
        $this->title = __('Core::sysColor.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysColor');
        $this->fieldsFilterable = [];
    

    }

    /**
     * Crée une nouvelle instance de sysColor.
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
    public function getSysColorStats(): array
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
            'table' => 'Core::sysColor._table',
            default => 'Core::sysColor._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sysColor_view_type', $default_view_type);
        $sysColor_viewType = $this->viewState->get('sysColor_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sysColor_view_type') === 'widgets') {
            $this->viewState->set("scope.sysColor.visible", 1);
        }else{
            $this->viewState->remove("scope.sysColor.visible");
        }
        
        // Récupération des données
        $sysColors_data = $this->paginate($params);
        $sysColors_stats = $this->getsysColorStats();
        $sysColors_filters = $this->getFieldsFilterable();
        $sysColor_instance = $this->createInstance();
        $sysColor_viewTypes = $this->getViewTypes();
        $sysColor_partialViewName = $this->getPartialViewName($sysColor_viewType);
        $sysColor_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sysColor.stats', $sysColors_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sysColor_viewTypes',
            'sysColor_viewType',
            'sysColors_data',
            'sysColors_stats',
            'sysColors_filters',
            'sysColor_instance',
            'sysColor_title',
            'contextKey'
        );
    
        return [
            'sysColors_data' => $sysColors_data,
            'sysColors_stats' => $sysColors_stats,
            'sysColors_filters' => $sysColors_filters,
            'sysColor_instance' => $sysColor_instance,
            'sysColor_viewType' => $sysColor_viewType,
            'sysColor_viewTypes' => $sysColor_viewTypes,
            'sysColor_partialViewName' => $sysColor_partialViewName,
            'contextKey' => $contextKey,
            'sysColor_compact_value' => $compact_value
        ];
    }

}
