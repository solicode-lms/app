<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgQcm\Models\PropositionReponse;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe PropositionReponseService pour gérer la persistance de l'entité PropositionReponse.
 */
class BasePropositionReponseService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour propositionReponses.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'reference',
        'libelle',
        'is_correcte',
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
     * Constructeur de la classe PropositionReponseService.
     */
    public function __construct()
    {
        parent::__construct(new PropositionReponse());
        $this->fieldsFilterable = [];
        $this->title = __('PkgQcm::propositionReponse.plural');
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
            $propositionReponse = $this->find($data['id']);
            $propositionReponse->fill($data);
        } else {
            $propositionReponse = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($propositionReponse->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $propositionReponse->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($propositionReponse->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($propositionReponse->id, $data);
            }
        }

        return $propositionReponse;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('propositionReponse');
        $this->fieldsFilterable = [];
        
            
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
     * Crée une nouvelle instance de propositionReponse.
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
    public function getPropositionReponseStats(): array
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
            'table' => 'PkgQcm::propositionReponse._table',
            default => 'PkgQcm::propositionReponse._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('propositionReponse_view_type', $default_view_type);
        $propositionReponse_viewType = $this->viewState->get('propositionReponse_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('propositionReponse_view_type') === 'widgets') {
            $this->viewState->set("scope.propositionReponse.visible", 1);
        }else{
            $this->viewState->remove("scope.propositionReponse.visible");
        }
        
        // Récupération des données
        $propositionReponses_data = $this->paginate($params);
        $propositionReponses_stats = $this->getpropositionReponseStats();
        $propositionReponses_total = $this->count();
        $propositionReponses_filters = $this->getFieldsFilterable();
        $propositionReponse_instance = $this->createInstance();
        $propositionReponse_viewTypes = $this->getViewTypes();
        $propositionReponse_partialViewName = $this->getPartialViewName($propositionReponse_viewType);
        $propositionReponse_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.propositionReponse.stats', $propositionReponses_stats);
    
        $propositionReponses_permissions = [

            'edit-propositionReponse' => Auth::user()->can('edit-propositionReponse'),
            'destroy-propositionReponse' => Auth::user()->can('destroy-propositionReponse'),
            'show-propositionReponse' => Auth::user()->can('show-propositionReponse'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $propositionReponses_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($propositionReponses_data as $item) {
                $propositionReponses_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'propositionReponse_viewTypes',
            'propositionReponse_viewType',
            'propositionReponses_data',
            'propositionReponses_stats',
            'propositionReponses_total',
            'propositionReponses_filters',
            'propositionReponse_instance',
            'propositionReponse_title',
            'contextKey',
            'propositionReponses_permissions',
            'propositionReponses_permissionsByItem'
        );
    
        return [
            'propositionReponses_data' => $propositionReponses_data,
            'propositionReponses_stats' => $propositionReponses_stats,
            'propositionReponses_total' => $propositionReponses_total,
            'propositionReponses_filters' => $propositionReponses_filters,
            'propositionReponse_instance' => $propositionReponse_instance,
            'propositionReponse_viewType' => $propositionReponse_viewType,
            'propositionReponse_viewTypes' => $propositionReponse_viewTypes,
            'propositionReponse_partialViewName' => $propositionReponse_partialViewName,
            'contextKey' => $contextKey,
            'propositionReponse_compact_value' => $compact_value,
            'propositionReponses_permissions' => $propositionReponses_permissions,
            'propositionReponses_permissionsByItem' => $propositionReponses_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $propositionReponse_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $propositionReponse_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($propositionReponse_ids as $id) {
            $propositionReponse = $this->find($id);
            $this->authorize('update', $propositionReponse);
    
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
            'libelle',
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
    public function buildFieldMeta(PropositionReponse $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgQcm\App\Requests\PropositionReponseRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'proposition_reponse',
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

            case 'libelle':
                return $this->computeFieldMeta($e, $field, $meta, 'text');

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
    public function applyInlinePatch(PropositionReponse $e, array $changes): PropositionReponse
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
    public function formatDisplayValues(PropositionReponse $e, array $fields): array
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
                case 'libelle':
                    $html = view('Core::fields_by_type.text', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
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
