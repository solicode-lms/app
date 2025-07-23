<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCompetences\Models\CritereEvaluation;
use Modules\Core\Services\BaseService;

/**
 * Classe CritereEvaluationService pour gérer la persistance de l'entité CritereEvaluation.
 */
class BaseCritereEvaluationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour critereEvaluations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'unite_apprentissage_id',
        'intitule',
        'bareme',
        'phase_evaluation_id'
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
     * Constructeur de la classe CritereEvaluationService.
     */
    public function __construct()
    {
        parent::__construct(new CritereEvaluation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::critereEvaluation.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('critereEvaluation');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::uniteApprentissage.plural"), 'unite_apprentissage_id', \Modules\PkgCompetences\Models\UniteApprentissage::class, 'code');
        }

        if (!array_key_exists('phase_evaluation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::phaseEvaluation.plural"), 'phase_evaluation_id', \Modules\PkgCompetences\Models\PhaseEvaluation::class, 'code');
        }

    }

    /**
     * Crée une nouvelle instance de critereEvaluation.
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
    public function getCritereEvaluationStats(): array
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
            'table' => 'PkgCompetences::critereEvaluation._table',
            default => 'PkgCompetences::critereEvaluation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('critereEvaluation_view_type', $default_view_type);
        $critereEvaluation_viewType = $this->viewState->get('critereEvaluation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('critereEvaluation_view_type') === 'widgets') {
            $this->viewState->set("scope.critereEvaluation.visible", 1);
        }else{
            $this->viewState->remove("scope.critereEvaluation.visible");
        }
        
        // Récupération des données
        $critereEvaluations_data = $this->paginate($params);
        $critereEvaluations_stats = $this->getcritereEvaluationStats();
        $critereEvaluations_filters = $this->getFieldsFilterable();
        $critereEvaluation_instance = $this->createInstance();
        $critereEvaluation_viewTypes = $this->getViewTypes();
        $critereEvaluation_partialViewName = $this->getPartialViewName($critereEvaluation_viewType);
        $critereEvaluation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.critereEvaluation.stats', $critereEvaluations_stats);
    
        $critereEvaluations_permissions = [

            'edit-critereEvaluation' => Auth::user()->can('edit-critereEvaluation'),
            'destroy-critereEvaluation' => Auth::user()->can('destroy-critereEvaluation'),
            'show-critereEvaluation' => Auth::user()->can('show-critereEvaluation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $critereEvaluations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($critereEvaluations_data as $item) {
                $critereEvaluations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'critereEvaluation_viewTypes',
            'critereEvaluation_viewType',
            'critereEvaluations_data',
            'critereEvaluations_stats',
            'critereEvaluations_filters',
            'critereEvaluation_instance',
            'critereEvaluation_title',
            'contextKey',
            'critereEvaluations_permissions',
            'critereEvaluations_permissionsByItem'
        );
    
        return [
            'critereEvaluations_data' => $critereEvaluations_data,
            'critereEvaluations_stats' => $critereEvaluations_stats,
            'critereEvaluations_filters' => $critereEvaluations_filters,
            'critereEvaluation_instance' => $critereEvaluation_instance,
            'critereEvaluation_viewType' => $critereEvaluation_viewType,
            'critereEvaluation_viewTypes' => $critereEvaluation_viewTypes,
            'critereEvaluation_partialViewName' => $critereEvaluation_partialViewName,
            'contextKey' => $contextKey,
            'critereEvaluation_compact_value' => $compact_value,
            'critereEvaluations_permissions' => $critereEvaluations_permissions,
            'critereEvaluations_permissionsByItem' => $critereEvaluations_permissionsByItem
        ];
    }

}
