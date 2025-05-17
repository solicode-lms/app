<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\Services\Base;

use Modules\PkgValidationProjets\Models\EvaluationRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe EvaluationRealisationTacheService pour gérer la persistance de l'entité EvaluationRealisationTache.
 */
class BaseEvaluationRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour evaluationRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'note',
        'message',
        'evaluateur_id',
        'realisation_tache_id'
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
     * Constructeur de la classe EvaluationRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new EvaluationRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgValidationProjets::evaluationRealisationTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('evaluationRealisationTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('evaluateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgValidationProjets::evaluateur.plural"), 'evaluateur_id', \Modules\PkgValidationProjets\Models\Evaluateur::class, 'nom');
        }

        if (!array_key_exists('realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::realisationTache.plural"), 'realisation_tache_id', \Modules\PkgGestionTaches\Models\RealisationTache::class, 'id');
        }

    }

    /**
     * Crée une nouvelle instance de evaluationRealisationTache.
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
    public function getEvaluationRealisationTacheStats(): array
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
            'table' => 'PkgValidationProjets::evaluationRealisationTache._table',
            default => 'PkgValidationProjets::evaluationRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('evaluationRealisationTache_view_type', $default_view_type);
        $evaluationRealisationTache_viewType = $this->viewState->get('evaluationRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('evaluationRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.evaluationRealisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.evaluationRealisationTache.visible");
        }
        
        // Récupération des données
        $evaluationRealisationTaches_data = $this->paginate($params);
        $evaluationRealisationTaches_stats = $this->getevaluationRealisationTacheStats();
        $evaluationRealisationTaches_filters = $this->getFieldsFilterable();
        $evaluationRealisationTache_instance = $this->createInstance();
        $evaluationRealisationTache_viewTypes = $this->getViewTypes();
        $evaluationRealisationTache_partialViewName = $this->getPartialViewName($evaluationRealisationTache_viewType);
        $evaluationRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.evaluationRealisationTache.stats', $evaluationRealisationTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'evaluationRealisationTache_viewTypes',
            'evaluationRealisationTache_viewType',
            'evaluationRealisationTaches_data',
            'evaluationRealisationTaches_stats',
            'evaluationRealisationTaches_filters',
            'evaluationRealisationTache_instance',
            'evaluationRealisationTache_title',
            'contextKey'
        );
    
        return [
            'evaluationRealisationTaches_data' => $evaluationRealisationTaches_data,
            'evaluationRealisationTaches_stats' => $evaluationRealisationTaches_stats,
            'evaluationRealisationTaches_filters' => $evaluationRealisationTaches_filters,
            'evaluationRealisationTache_instance' => $evaluationRealisationTache_instance,
            'evaluationRealisationTache_viewType' => $evaluationRealisationTache_viewType,
            'evaluationRealisationTache_viewTypes' => $evaluationRealisationTache_viewTypes,
            'evaluationRealisationTache_partialViewName' => $evaluationRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'evaluationRealisationTache_compact_value' => $compact_value
        ];
    }

}
