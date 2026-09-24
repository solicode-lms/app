<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\Question;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe QuestionService pour gérer la persistance de l'entité Question.
 */
class BaseQuestionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour questions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'reference',
        'enonce',
        'type',
        'explication',
        'is_actif',
        'bareme',
        'qcm_id',
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
     * Constructeur de la classe QuestionService.
     */
    public function __construct()
    {
        parent::__construct(new Question());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::question.plural');
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
            $question = $this->find($data['id']);
            $question->fill($data);
        } else {
            $question = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($question->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $question->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($question->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($question->id, $data);
            }
        }

        return $question;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('question');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('qcm_id', $scopeVariables)) {


                    $qcmService = new \Modules\PkgQcm\Services\QcmService();
                    $qcmIds = $this->getAvailableFilterValues('qcm_id');
                    $qcms = $qcmService->getByIds($qcmIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgQcm::qcm.plural"), 
                        'qcm_id', 
                        \Modules\PkgQcm\Models\Qcm::class, 
                        'titre',
                        $qcms
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
     * Crée une nouvelle instance de question.
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
    public function getQuestionStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function importIaForm(int $questionId)
    {
        $question = $this->find($questionId);
        if (!$question) {
            return false; 
        }
        $value =  $question->save();
        $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
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
            'table' => 'PkgQcm::question._table',
            default => 'PkgQcm::question._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('question_view_type', $default_view_type);
        $question_viewType = $this->viewState->get('question_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('question_view_type') === 'widgets') {
            $this->viewState->set("scope.question.visible", 1);
        }else{
            $this->viewState->remove("scope.question.visible");
        }
        
        // Récupération des données
        $questions_data = $this->paginate($params);
        $questions_stats = $this->getquestionStats();
        $questions_total = $this->count();
        $questions_filters = $this->getFieldsFilterable();
        $question_instance = $this->createInstance();
        $question_viewTypes = $this->getViewTypes();
        $question_partialViewName = $this->getPartialViewName($question_viewType);
        $question_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.question.stats', $questions_stats);
    
        $questions_permissions = [
            'importIaForm-question' => Auth::user()->can('importIaForm-question'),           
            
            'edit-question' => Auth::user()->can('edit-question'),
            'destroy-question' => Auth::user()->can('destroy-question'),
            'show-question' => Auth::user()->can('show-question'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $questions_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($questions_data as $item) {
                $questions_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'question_viewTypes',
            'question_viewType',
            'questions_data',
            'questions_stats',
            'questions_total',
            'questions_filters',
            'question_instance',
            'question_title',
            'contextKey',
            'questions_permissions',
            'questions_permissionsByItem'
        );
    
        return [
            'questions_data' => $questions_data,
            'questions_stats' => $questions_stats,
            'questions_total' => $questions_total,
            'questions_filters' => $questions_filters,
            'question_instance' => $question_instance,
            'question_viewType' => $question_viewType,
            'question_viewTypes' => $question_viewTypes,
            'question_partialViewName' => $question_partialViewName,
            'contextKey' => $contextKey,
            'question_compact_value' => $compact_value,
            'questions_permissions' => $questions_permissions,
            'questions_permissionsByItem' => $questions_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $question_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $question_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($question_ids as $id) {
            $question = $this->find($id);
            $this->authorize('update', $question);
    
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
        // Champs considérés comme inline
        $inlineFields = [
            'ordre',
            'qcm_id',
            'unite_apprentissage_id'
        ];

        // Récupération des champs autorisés par rôle via getFieldsEditable()
        return array_values(array_intersect(
            $inlineFields,
            $this->getFieldsEditable()
        ));
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(Question $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\QuestionRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'question',
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

            case 'qcm_id':
                 $values = (new \Modules\PkgQcm\Services\QcmService())
                    ->getAllForSelect($e->qcm)
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
    public function applyInlinePatch(Question $e, array $changes): Question
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
    public function formatDisplayValues(Question $e, array $fields): array
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
                case 'qcm_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'qcm'
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
