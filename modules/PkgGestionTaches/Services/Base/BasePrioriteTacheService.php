<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\PrioriteTache;
use Modules\Core\Services\BaseService;

/**
 * Classe PrioriteTacheService pour gérer la persistance de l'entité PrioriteTache.
 */
class BasePrioriteTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour prioriteTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'ordre',
        'description',
        'formateur_id'
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
     * Constructeur de la classe PrioriteTacheService.
     */
    public function __construct()
    {
        parent::__construct(new PrioriteTache());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('prioriteTache');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de prioriteTache.
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
    public function getPrioriteTacheStats(): array
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
            'table' => 'PkgGestionTaches::prioriteTache._table',
            default => 'PkgGestionTaches::prioriteTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('prioriteTache_view_type', $default_view_type);
        $prioriteTache_viewType = $this->viewState->get('prioriteTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('prioriteTache_view_type') === 'widgets') {
            $this->viewState->set("filter.prioriteTache.visible", 1);
        }
        
        // Récupération des données
        $prioriteTaches_data = $this->paginate($params);
        $prioriteTaches_stats = $this->getprioriteTacheStats();
        $prioriteTaches_filters = $this->getFieldsFilterable();
        $prioriteTache_instance = $this->createInstance();
        $prioriteTache_viewTypes = $this->getViewTypes();
        $prioriteTache_partialViewName = $this->getPartialViewName($prioriteTache_viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.prioriteTache.stats', $prioriteTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'prioriteTache_viewTypes',
            'prioriteTache_viewType',
            'prioriteTaches_data',
            'prioriteTaches_stats',
            'prioriteTaches_filters',
            'prioriteTache_instance'
        );
    
        return [
            'prioriteTaches_data' => $prioriteTaches_data,
            'prioriteTaches_stats' => $prioriteTaches_stats,
            'prioriteTaches_filters' => $prioriteTaches_filters,
            'prioriteTache_instance' => $prioriteTache_instance,
            'prioriteTache_viewType' => $prioriteTache_viewType,
            'prioriteTache_viewTypes' => $prioriteTache_viewTypes,
            'prioriteTache_partialViewName' => $prioriteTache_partialViewName,
            'prioriteTache_compact_value' => $compact_value
        ];
    }

}
