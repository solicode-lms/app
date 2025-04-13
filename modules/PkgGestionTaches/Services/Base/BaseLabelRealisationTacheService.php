<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\LabelRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe LabelRealisationTacheService pour gérer la persistance de l'entité LabelRealisationTache.
 */
class BaseLabelRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour labelRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'formateur_id',
        'sys_color_id'
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
     * Constructeur de la classe LabelRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new LabelRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::labelRealisationTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('labelRealisationTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }



        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }


    }

    /**
     * Crée une nouvelle instance de labelRealisationTache.
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
    public function getLabelRealisationTacheStats(): array
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
            'table' => 'PkgGestionTaches::labelRealisationTache._table',
            default => 'PkgGestionTaches::labelRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('labelRealisationTache_view_type', $default_view_type);
        $labelRealisationTache_viewType = $this->viewState->get('labelRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('labelRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("filter.labelRealisationTache.visible", 1);
        }
        
        // Récupération des données
        $labelRealisationTaches_data = $this->paginate($params);
        $labelRealisationTaches_stats = $this->getlabelRealisationTacheStats();
        $labelRealisationTaches_filters = $this->getFieldsFilterable();
        $labelRealisationTache_instance = $this->createInstance();
        $labelRealisationTache_viewTypes = $this->getViewTypes();
        $labelRealisationTache_partialViewName = $this->getPartialViewName($labelRealisationTache_viewType);
        $labelRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.labelRealisationTache.stats', $labelRealisationTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'labelRealisationTache_viewTypes',
            'labelRealisationTache_viewType',
            'labelRealisationTaches_data',
            'labelRealisationTaches_stats',
            'labelRealisationTaches_filters',
            'labelRealisationTache_instance',
            'labelRealisationTache_title',
            'contextKey'
        );
    
        return [
            'labelRealisationTaches_data' => $labelRealisationTaches_data,
            'labelRealisationTaches_stats' => $labelRealisationTaches_stats,
            'labelRealisationTaches_filters' => $labelRealisationTaches_filters,
            'labelRealisationTache_instance' => $labelRealisationTache_instance,
            'labelRealisationTache_viewType' => $labelRealisationTache_viewType,
            'labelRealisationTache_viewTypes' => $labelRealisationTache_viewTypes,
            'labelRealisationTache_partialViewName' => $labelRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'labelRealisationTache_compact_value' => $compact_value
        ];
    }

}
