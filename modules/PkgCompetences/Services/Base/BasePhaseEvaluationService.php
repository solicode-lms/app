<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCompetences\Models\PhaseEvaluation;
use Modules\Core\Services\BaseService;

/**
 * Classe PhaseEvaluationService pour gérer la persistance de l'entité PhaseEvaluation.
 */
class BasePhaseEvaluationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour phaseEvaluations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'libelle',
        'coefficient',
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
     * Constructeur de la classe PhaseEvaluationService.
     */
    public function __construct()
    {
        parent::__construct(new PhaseEvaluation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::phaseEvaluation.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('phaseEvaluation');
        $this->fieldsFilterable = [];
    

    }

    /**
     * Crée une nouvelle instance de phaseEvaluation.
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
    public function getPhaseEvaluationStats(): array
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
            'table' => 'PkgCompetences::phaseEvaluation._table',
            default => 'PkgCompetences::phaseEvaluation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('phaseEvaluation_view_type', $default_view_type);
        $phaseEvaluation_viewType = $this->viewState->get('phaseEvaluation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('phaseEvaluation_view_type') === 'widgets') {
            $this->viewState->set("scope.phaseEvaluation.visible", 1);
        }else{
            $this->viewState->remove("scope.phaseEvaluation.visible");
        }
        
        // Récupération des données
        $phaseEvaluations_data = $this->paginate($params);
        $phaseEvaluations_stats = $this->getphaseEvaluationStats();
        $phaseEvaluations_filters = $this->getFieldsFilterable();
        $phaseEvaluation_instance = $this->createInstance();
        $phaseEvaluation_viewTypes = $this->getViewTypes();
        $phaseEvaluation_partialViewName = $this->getPartialViewName($phaseEvaluation_viewType);
        $phaseEvaluation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.phaseEvaluation.stats', $phaseEvaluations_stats);
    
        $phaseEvaluations_permissions = [

            'edit-phaseEvaluation' => Auth::user()->can('edit-phaseEvaluation'),
            'destroy-phaseEvaluation' => Auth::user()->can('destroy-phaseEvaluation'),
            'show-phaseEvaluation' => Auth::user()->can('show-phaseEvaluation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $phaseEvaluations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($phaseEvaluations_data as $item) {
                $phaseEvaluations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'phaseEvaluation_viewTypes',
            'phaseEvaluation_viewType',
            'phaseEvaluations_data',
            'phaseEvaluations_stats',
            'phaseEvaluations_filters',
            'phaseEvaluation_instance',
            'phaseEvaluation_title',
            'contextKey',
            'phaseEvaluations_permissions',
            'phaseEvaluations_permissionsByItem'
        );
    
        return [
            'phaseEvaluations_data' => $phaseEvaluations_data,
            'phaseEvaluations_stats' => $phaseEvaluations_stats,
            'phaseEvaluations_filters' => $phaseEvaluations_filters,
            'phaseEvaluation_instance' => $phaseEvaluation_instance,
            'phaseEvaluation_viewType' => $phaseEvaluation_viewType,
            'phaseEvaluation_viewTypes' => $phaseEvaluation_viewTypes,
            'phaseEvaluation_partialViewName' => $phaseEvaluation_partialViewName,
            'contextKey' => $contextKey,
            'phaseEvaluation_compact_value' => $compact_value,
            'phaseEvaluations_permissions' => $phaseEvaluations_permissions,
            'phaseEvaluations_permissionsByItem' => $phaseEvaluations_permissionsByItem
        ];
    }

}
