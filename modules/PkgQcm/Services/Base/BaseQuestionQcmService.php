<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\QuestionQcm;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe QuestionQcmService pour gérer la persistance de l'entité QuestionQcm.
 */
class BaseQuestionQcmService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour questionQcms.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'reference',
        'bareme',
        'qcm_id',
        'question_lib_id'
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
     * Constructeur de la classe QuestionQcmService.
     */
    public function __construct()
    {
        parent::__construct(new QuestionQcm());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::questionQcm.plural');
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
            $questionQcm = $this->find($data['id']);
            $questionQcm->fill($data);
        } else {
            $questionQcm = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($questionQcm->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $questionQcm->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($questionQcm->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($questionQcm->id, $data);
            }
        }

        return $questionQcm;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('questionQcm');
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
            
            
                if (!array_key_exists('question_lib_id', $scopeVariables)) {


                    $questionLibService = new \Modules\PkgQcm\Services\QuestionLibService();
                    $questionLibIds = $this->getAvailableFilterValues('question_lib_id');
                    $questionLibs = $questionLibService->getByIds($questionLibIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgQcm::questionLib.plural"), 
                        'question_lib_id', 
                        \Modules\PkgQcm\Models\QuestionLib::class, 
                        'type',
                        $questionLibs
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de questionQcm.
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
    public function getQuestionQcmStats(): array
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
            'table' => 'PkgQcm::questionQcm._table',
            default => 'PkgQcm::questionQcm._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('questionQcm_view_type', $default_view_type);
        $questionQcm_viewType = $this->viewState->get('questionQcm_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('questionQcm_view_type') === 'widgets') {
            $this->viewState->set("scope.questionQcm.visible", 1);
        }else{
            $this->viewState->remove("scope.questionQcm.visible");
        }
        
        // Récupération des données
        $questionQcms_data = $this->paginate($params);
        $questionQcms_stats = $this->getquestionQcmStats();
        $questionQcms_total = $this->count();
        $questionQcms_filters = $this->getFieldsFilterable();
        $questionQcm_instance = $this->createInstance();
        $questionQcm_viewTypes = $this->getViewTypes();
        $questionQcm_partialViewName = $this->getPartialViewName($questionQcm_viewType);
        $questionQcm_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.questionQcm.stats', $questionQcms_stats);
    
        $questionQcms_permissions = [

            'edit-questionQcm' => Auth::user()->can('edit-questionQcm'),
            'destroy-questionQcm' => Auth::user()->can('destroy-questionQcm'),
            'show-questionQcm' => Auth::user()->can('show-questionQcm'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $questionQcms_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($questionQcms_data as $item) {
                $questionQcms_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'questionQcm_viewTypes',
            'questionQcm_viewType',
            'questionQcms_data',
            'questionQcms_stats',
            'questionQcms_total',
            'questionQcms_filters',
            'questionQcm_instance',
            'questionQcm_title',
            'contextKey',
            'questionQcms_permissions',
            'questionQcms_permissionsByItem'
        );
    
        return [
            'questionQcms_data' => $questionQcms_data,
            'questionQcms_stats' => $questionQcms_stats,
            'questionQcms_total' => $questionQcms_total,
            'questionQcms_filters' => $questionQcms_filters,
            'questionQcm_instance' => $questionQcm_instance,
            'questionQcm_viewType' => $questionQcm_viewType,
            'questionQcm_viewTypes' => $questionQcm_viewTypes,
            'questionQcm_partialViewName' => $questionQcm_partialViewName,
            'contextKey' => $contextKey,
            'questionQcm_compact_value' => $compact_value,
            'questionQcms_permissions' => $questionQcms_permissions,
            'questionQcms_permissionsByItem' => $questionQcms_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $questionQcm_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $questionQcm_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($questionQcm_ids as $id) {
            $questionQcm = $this->find($id);
            $this->authorize('update', $questionQcm);
    
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
            'question_lib_id'
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
    public function buildFieldMeta(QuestionQcm $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\QuestionQcmRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'question_qcm',
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
            case 'question_lib_id':
                 $values = (new \Modules\PkgQcm\Services\QuestionLibService())
                    ->getAllForSelect($e->questionLib)
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
    public function applyInlinePatch(QuestionQcm $e, array $changes): QuestionQcm
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
    public function formatDisplayValues(QuestionQcm $e, array $fields): array
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



                case 'question_lib_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'questionLib'
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
