<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\ReponseQcm;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe ReponseQcmService pour gérer la persistance de l'entité ReponseQcm.
 */
class BaseReponseQcmService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour reponseQcms.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'realisation_qcm_id',
        'date_reponse',
        'question_id'
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
     * Constructeur de la classe ReponseQcmService.
     */
    public function __construct()
    {
        parent::__construct(new ReponseQcm());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::reponseQcm.plural');
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
            $reponseQcm = $this->find($data['id']);
            $reponseQcm->fill($data);
        } else {
            $reponseQcm = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($reponseQcm->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $reponseQcm->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($reponseQcm->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($reponseQcm->id, $data);
            }
        }

        return $reponseQcm;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('reponseQcm');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('realisation_qcm_id', $scopeVariables)) {


                    $realisationQcmService = new \Modules\PkgQcm\Services\RealisationQcmService();
                    $realisationQcmIds = $this->getAvailableFilterValues('realisation_qcm_id');
                    $realisationQcms = $realisationQcmService->getByIds($realisationQcmIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgQcm::realisationQcm.plural"), 
                        'realisation_qcm_id', 
                        \Modules\PkgQcm\Models\RealisationQcm::class, 
                        'id',
                        $realisationQcms
                    );
                }
            
            
                if (!array_key_exists('question_id', $scopeVariables)) {


                    $questionService = new \Modules\PkgQcm\Services\QuestionService();
                    $questionIds = $this->getAvailableFilterValues('question_id');
                    $questions = $questionService->getByIds($questionIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgQcm::question.plural"), 
                        'question_id', 
                        \Modules\PkgQcm\Models\Question::class, 
                        'type',
                        $questions
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de reponseQcm.
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
    public function getReponseQcmStats(): array
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
            'table' => 'PkgQcm::reponseQcm._table',
            default => 'PkgQcm::reponseQcm._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('reponseQcm_view_type', $default_view_type);
        $reponseQcm_viewType = $this->viewState->get('reponseQcm_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('reponseQcm_view_type') === 'widgets') {
            $this->viewState->set("scope.reponseQcm.visible", 1);
        }else{
            $this->viewState->remove("scope.reponseQcm.visible");
        }
        
        // Récupération des données
        $reponseQcms_data = $this->paginate($params);
        $reponseQcms_stats = $this->getreponseQcmStats();
        $reponseQcms_total = $this->count();
        $reponseQcms_filters = $this->getFieldsFilterable();
        $reponseQcm_instance = $this->createInstance();
        $reponseQcm_viewTypes = $this->getViewTypes();
        $reponseQcm_partialViewName = $this->getPartialViewName($reponseQcm_viewType);
        $reponseQcm_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.reponseQcm.stats', $reponseQcms_stats);
    
        $reponseQcms_permissions = [

            'edit-reponseQcm' => Auth::user()->can('edit-reponseQcm'),
            'destroy-reponseQcm' => Auth::user()->can('destroy-reponseQcm'),
            'show-reponseQcm' => Auth::user()->can('show-reponseQcm'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $reponseQcms_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($reponseQcms_data as $item) {
                $reponseQcms_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'reponseQcm_viewTypes',
            'reponseQcm_viewType',
            'reponseQcms_data',
            'reponseQcms_stats',
            'reponseQcms_total',
            'reponseQcms_filters',
            'reponseQcm_instance',
            'reponseQcm_title',
            'contextKey',
            'reponseQcms_permissions',
            'reponseQcms_permissionsByItem'
        );
    
        return [
            'reponseQcms_data' => $reponseQcms_data,
            'reponseQcms_stats' => $reponseQcms_stats,
            'reponseQcms_total' => $reponseQcms_total,
            'reponseQcms_filters' => $reponseQcms_filters,
            'reponseQcm_instance' => $reponseQcm_instance,
            'reponseQcm_viewType' => $reponseQcm_viewType,
            'reponseQcm_viewTypes' => $reponseQcm_viewTypes,
            'reponseQcm_partialViewName' => $reponseQcm_partialViewName,
            'contextKey' => $contextKey,
            'reponseQcm_compact_value' => $compact_value,
            'reponseQcms_permissions' => $reponseQcms_permissions,
            'reponseQcms_permissionsByItem' => $reponseQcms_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $reponseQcm_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $reponseQcm_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($reponseQcm_ids as $id) {
            $reponseQcm = $this->find($id);
            $this->authorize('update', $reponseQcm);
    
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
            'realisation_qcm_id',
            'question_id'
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
    public function buildFieldMeta(ReponseQcm $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\ReponseQcmRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'reponse_qcm',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'realisation_qcm_id':
                 $values = (new \Modules\PkgQcm\Services\RealisationQcmService())
                    ->getAllForSelect($e->realisationQcm)
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
            case 'question_id':
                 $values = (new \Modules\PkgQcm\Services\QuestionService())
                    ->getAllForSelect($e->question)
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
    public function applyInlinePatch(ReponseQcm $e, array $changes): ReponseQcm
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
    public function formatDisplayValues(ReponseQcm $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'realisation_qcm_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'realisationQcm'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'question_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'question'
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
