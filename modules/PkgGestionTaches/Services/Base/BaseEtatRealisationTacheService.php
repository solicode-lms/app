<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\EtatRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatRealisationTacheService pour gérer la persistance de l'entité EtatRealisationTache.
 */
class BaseEtatRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'workflow_tache_id',
        'sys_color_id',
        'is_editable_only_by_formateur',
        'formateur_id',
        'description'
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
     * Constructeur de la classe EtatRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::etatRealisationTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('workflow_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::workflowTache.plural"), 'workflow_tache_id', \Modules\PkgGestionTaches\Models\WorkflowTache::class, 'code');
        }



        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }



        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }


    }

    /**
     * Crée une nouvelle instance de etatRealisationTache.
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
    public function getEtatRealisationTacheStats(): array
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
            'table' => 'PkgGestionTaches::etatRealisationTache._table',
            default => 'PkgGestionTaches::etatRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('etatRealisationTache_view_type', $default_view_type);
        $etatRealisationTache_viewType = $this->viewState->get('etatRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("filter.etatRealisationTache.visible", 1);
        }
        
        // Récupération des données
        $etatRealisationTaches_data = $this->paginate($params);
        $etatRealisationTaches_stats = $this->getetatRealisationTacheStats();
        $etatRealisationTaches_filters = $this->getFieldsFilterable();
        $etatRealisationTache_instance = $this->createInstance();
        $etatRealisationTache_viewTypes = $this->getViewTypes();
        $etatRealisationTache_partialViewName = $this->getPartialViewName($etatRealisationTache_viewType);
        $etatRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationTache.stats', $etatRealisationTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationTache_viewTypes',
            'etatRealisationTache_viewType',
            'etatRealisationTaches_data',
            'etatRealisationTaches_stats',
            'etatRealisationTaches_filters',
            'etatRealisationTache_instance',
            'etatRealisationTache_title',
            'contextKey'
        );
    
        return [
            'etatRealisationTaches_data' => $etatRealisationTaches_data,
            'etatRealisationTaches_stats' => $etatRealisationTaches_stats,
            'etatRealisationTaches_filters' => $etatRealisationTaches_filters,
            'etatRealisationTache_instance' => $etatRealisationTache_instance,
            'etatRealisationTache_viewType' => $etatRealisationTache_viewType,
            'etatRealisationTache_viewTypes' => $etatRealisationTache_viewTypes,
            'etatRealisationTache_partialViewName' => $etatRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationTache_compact_value' => $compact_value
        ];
    }

}
