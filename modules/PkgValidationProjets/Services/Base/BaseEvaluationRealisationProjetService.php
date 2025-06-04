<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\Services\Base;

use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe EvaluationRealisationProjetService pour gérer la persistance de l'entité EvaluationRealisationProjet.
 */
class BaseEvaluationRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour evaluationRealisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'date_evaluation',
        'remarques',
        'realisation_projet_id',
        'evaluateur_id',
        'etat_evaluation_projet_id'
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
     * Constructeur de la classe EvaluationRealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EvaluationRealisationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgValidationProjets::evaluationRealisationProjet.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('evaluationRealisationProjet');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('realisation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::realisationProjet.plural"), 'realisation_projet_id', \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 'id');
        }

        if (!array_key_exists('evaluateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgValidationProjets::evaluateur.plural"), 'evaluateur_id', \Modules\PkgValidationProjets\Models\Evaluateur::class, 'nom');
        }

        if (!array_key_exists('etat_evaluation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgValidationProjets::etatEvaluationProjet.plural"), 'etat_evaluation_projet_id', \Modules\PkgValidationProjets\Models\EtatEvaluationProjet::class, 'code');
        }

    }

    /**
     * Crée une nouvelle instance de evaluationRealisationProjet.
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
    public function getEvaluationRealisationProjetStats(): array
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
            'table' => 'PkgValidationProjets::evaluationRealisationProjet._table',
            default => 'PkgValidationProjets::evaluationRealisationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('evaluationRealisationProjet_view_type', $default_view_type);
        $evaluationRealisationProjet_viewType = $this->viewState->get('evaluationRealisationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('evaluationRealisationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.evaluationRealisationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.evaluationRealisationProjet.visible");
        }
        
        // Récupération des données
        $evaluationRealisationProjets_data = $this->paginate($params);
        $evaluationRealisationProjets_stats = $this->getevaluationRealisationProjetStats();
        $evaluationRealisationProjets_filters = $this->getFieldsFilterable();
        $evaluationRealisationProjet_instance = $this->createInstance();
        $evaluationRealisationProjet_viewTypes = $this->getViewTypes();
        $evaluationRealisationProjet_partialViewName = $this->getPartialViewName($evaluationRealisationProjet_viewType);
        $evaluationRealisationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.evaluationRealisationProjet.stats', $evaluationRealisationProjets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'evaluationRealisationProjet_viewTypes',
            'evaluationRealisationProjet_viewType',
            'evaluationRealisationProjets_data',
            'evaluationRealisationProjets_stats',
            'evaluationRealisationProjets_filters',
            'evaluationRealisationProjet_instance',
            'evaluationRealisationProjet_title',
            'contextKey'
        );
    
        return [
            'evaluationRealisationProjets_data' => $evaluationRealisationProjets_data,
            'evaluationRealisationProjets_stats' => $evaluationRealisationProjets_stats,
            'evaluationRealisationProjets_filters' => $evaluationRealisationProjets_filters,
            'evaluationRealisationProjet_instance' => $evaluationRealisationProjet_instance,
            'evaluationRealisationProjet_viewType' => $evaluationRealisationProjet_viewType,
            'evaluationRealisationProjet_viewTypes' => $evaluationRealisationProjet_viewTypes,
            'evaluationRealisationProjet_partialViewName' => $evaluationRealisationProjet_partialViewName,
            'contextKey' => $contextKey,
            'evaluationRealisationProjet_compact_value' => $compact_value
        ];
    }

}
