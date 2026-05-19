<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\Models\Competence;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe CompetenceService pour gérer la persistance de l'entité Competence.
 */
class BaseCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour competences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'mini_code',
        'nom',
        'module_id',
        'description',
        'reference'
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
     * Constructeur de la classe CompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new Competence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::competence.plural');
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
            $competence = $this->find($data['id']);
            $competence->fill($data);
        } else {
            $competence = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($competence->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $competence->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($competence->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($competence->id, $data);
            }
        }

        return $competence;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('competence');
        $this->fieldsFilterable = [];
        
            
                $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                $filiereIds = $this->getAvailableFilterValues('Module.Filiere_id');
                $filieres = $filiereService->getByIds($filiereIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgFormation::filiere.plural"),
                    'Module.Filiere_id', 
                    \Modules\PkgFormation\Models\Filiere::class,
                    "id", 
                    "id",
                    $filieres
                );
            



    }


    /**
     * Crée une nouvelle instance de competence.
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
    public function getCompetenceStats(): array
    {

        $stats = $this->initStats();

        // Ajouter les statistiques du propriétaire
        //$contexteState = $this->getContextState();
        // if ($contexteState !== null) {
        //     $stats[] = $contexteState;
        // }
        
            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Filiere::class,
                'modules.competences',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

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
            'table' => 'PkgCompetences::competence._table',
            default => 'PkgCompetences::competence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('competence_view_type', $default_view_type);
        $competence_viewType = $this->viewState->get('competence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('competence_view_type') === 'widgets') {
            $this->viewState->set("scope.competence.visible", 1);
        }else{
            $this->viewState->remove("scope.competence.visible");
        }
        
        // Récupération des données
        $competences_data = $this->paginate($params);
        $competences_stats = $this->getcompetenceStats();
        $competences_total = $this->count();
        $competences_filters = $this->getFieldsFilterable();
        $competence_instance = $this->createInstance();
        $competence_viewTypes = $this->getViewTypes();
        $competence_partialViewName = $this->getPartialViewName($competence_viewType);
        $competence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.competence.stats', $competences_stats);
    
        $competences_permissions = [

            'edit-competence' => Auth::user()->can('edit-competence'),
            'destroy-competence' => Auth::user()->can('destroy-competence'),
            'show-competence' => Auth::user()->can('show-competence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $competences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($competences_data as $item) {
                $competences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'competence_viewTypes',
            'competence_viewType',
            'competences_data',
            'competences_stats',
            'competences_total',
            'competences_filters',
            'competence_instance',
            'competence_title',
            'contextKey',
            'competences_permissions',
            'competences_permissionsByItem'
        );
    
        return [
            'competences_data' => $competences_data,
            'competences_stats' => $competences_stats,
            'competences_total' => $competences_total,
            'competences_filters' => $competences_filters,
            'competence_instance' => $competence_instance,
            'competence_viewType' => $competence_viewType,
            'competence_viewTypes' => $competence_viewTypes,
            'competence_partialViewName' => $competence_partialViewName,
            'contextKey' => $contextKey,
            'competence_compact_value' => $compact_value,
            'competences_permissions' => $competences_permissions,
            'competences_permissionsByItem' => $competences_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $competence_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $competence_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($competence_ids as $id) {
            $competence = $this->find($id);
            $this->authorize('update', $competence);
    
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
            'code',
            'mini_code',
            'nom',
            'module_id'
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
    public function buildFieldMeta(Competence $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCompetences\App\Requests\CompetenceRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'competence',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'code':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'mini_code':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'module_id':
                 $values = (new \Modules\PkgFormation\Services\ModuleService())
                    ->getAllForSelect($e->module)
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
    public function applyInlinePatch(Competence $e, array $changes): Competence
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
    public function formatDisplayValues(Competence $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'code':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'mini_code':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'nom':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'module_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'module'
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
