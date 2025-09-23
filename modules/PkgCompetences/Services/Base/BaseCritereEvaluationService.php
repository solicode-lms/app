<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\Models\CritereEvaluation;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

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
        'intitule',
        'bareme',
        'phase_evaluation_id',
        'unite_apprentissage_id'
    ];



    public function editableFieldsByRoles(): array
    {
        return [
        
        ];
    }


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


    /**
     * Applique les calculs dynamiques sur les champs marqués avec l’attribut `data-calcule`
     * pendant l’édition ou la création d’une entité.
     *
     * Cette méthode est utilisée dans les formulaires dynamiques pour recalculer certains champs
     * (ex : note, barème, état, progression...) en fonction des valeurs saisies ou modifiées.
     *
     * Elle est déclenchée automatiquement lorsqu’un champ du formulaire possède l’attribut `data-calcule`.
     *
     * @param mixed $data Données en cours d’édition (array ou modèle hydraté sans persistance).
     * @return mixed L’entité enrichie avec les champs recalculés.
     */
    public function dataCalcul($data)
    {
        // 🧾 Chargement ou initialisation de l'entité
        if (!empty($data['id'])) {
            $critereEvaluation = $this->find($data['id']);
            $critereEvaluation->fill($data);
        } else {
            $critereEvaluation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($critereEvaluation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $critereEvaluation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($critereEvaluation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($critereEvaluation->id, $data);
            }
        }

        return $critereEvaluation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('critereEvaluation');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('phase_evaluation_id', $scopeVariables)) {


                    $phaseEvaluationService = new \Modules\PkgCompetences\Services\PhaseEvaluationService();
                    $phaseEvaluationIds = $this->getAvailableFilterValues('phase_evaluation_id');
                    $phaseEvaluations = $phaseEvaluationService->getByIds($phaseEvaluationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::phaseEvaluation.plural"), 
                        'phase_evaluation_id', 
                        \Modules\PkgCompetences\Models\PhaseEvaluation::class, 
                        'code',
                        $phaseEvaluations
                    );
                }
            
            
                if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {


                    $uniteApprentissageService = new \Modules\PkgCompetences\Services\UniteApprentissageService();
                    $uniteApprentissageIds = $this->getAvailableFilterValues('unite_apprentissage_id');
                    $uniteApprentissages = $uniteApprentissageService->getByIds($uniteApprentissageIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::uniteApprentissage.plural"), 
                        'unite_apprentissage_id', 
                        \Modules\PkgCompetences\Models\UniteApprentissage::class, 
                        'code',
                        $uniteApprentissages
                    );
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
        $critereEvaluations_total = $this->count();
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
            'critereEvaluations_total',
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
            'critereEvaluations_total' => $critereEvaluations_total,
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

    public function bulkUpdateJob($token, $critereEvaluation_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $critereEvaluation_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($critereEvaluation_ids as $id) {
            $critereEvaluation = $this->find($id);
            $this->authorize('update', $critereEvaluation);
    
            $allFields = $this->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $valeursChamps[$field]])
                ->toArray();
    
            if (!empty($data)) {
                $this->updateOnlyExistanteAttribute($id, $data);
            }

            $jobManager->tick();
            
        }

        return "done";
    }

    /**
    * Liste des champs autorisés à l’édition inline
    */
    public function getInlineFieldsEditable(): array
    {
        return [
            'ordre',
            'intitule',
            'phase_evaluation_id',
            'unite_apprentissage_id'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(CritereEvaluation $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCompetences\App\Requests\CritereEvaluationRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'critere_evaluation',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'ordre':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'intitule':
                return $this->computeFieldMeta($e, $field, $meta, 'text');

            case 'phase_evaluation_id':
                 $values = (new \Modules\PkgCompetences\Services\PhaseEvaluationService())
                    ->getAllForSelect($e->phaseEvaluation)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            case 'unite_apprentissage_id':
                 $values = (new \Modules\PkgCompetences\Services\UniteApprentissageService())
                    ->getAllForSelect($e->uniteApprentissage)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', [
                    'required' => true,
                    'options'  => [
                        'source' => 'static',
                        'values' => $values,
                    ],
                ]);
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(CritereEvaluation $e, array $changes): CritereEvaluation
    {
        $allowed = $this->getInlineFieldsEditable();
        $filtered = Arr::only($changes, $allowed);

        if (empty($filtered)) {
            abort(422, 'Aucun champ autorisé.');
        }

        $rules = [];
        foreach ($filtered as $field => $value) {
            $meta = $this->buildFieldMeta($e, $field);
            $rules[$field] = $meta['validation'] ?? ['nullable'];
        }
        
        $e->fill($filtered);
        Validator::make($e->toArray(), $rules)->validate();
        $e = $this->updateOnlyExistanteAttribute($e->id, $filtered);

        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(CritereEvaluation $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'ordre':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'ordre'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'intitule':
                    // Vue custom définie pour ce champ
                    $html = view('PkgCompetences::critereEvaluation.custom.fields.intitule', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'phase_evaluation_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'phaseEvaluation'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'unite_apprentissage_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'uniteApprentissage'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;




                default:
                    // fallback générique si champ non pris en charge
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                    ])->render();

                    $out[$field] = ['html' => $html];
            }
        }
        return $out;
    }
}
