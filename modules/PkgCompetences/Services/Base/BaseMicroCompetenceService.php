<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCompetences\Models\MicroCompetence;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe MicroCompetenceService pour gérer la persistance de l'entité MicroCompetence.
 */
class BaseMicroCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour microCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'titre',
        'sous_titre',
        'competence_id',
        'lien',
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
     * Constructeur de la classe MicroCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new MicroCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::microCompetence.plural');
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
            $microCompetence = $this->find($data['id']);
            $microCompetence->fill($data);
        } else {
            $microCompetence = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($microCompetence->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $microCompetence->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($microCompetence->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($microCompetence->id, $data);
            }
        }

        return $microCompetence;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('microCompetence');
        $this->fieldsFilterable = [];
        
            
                $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                $filiereIds = $this->getAvailableFilterValues('competence.module.filiere_id');
                $filieres = $filiereService->getByIds($filiereIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgFormation::filiere.plural"),
                    'competence.module.filiere_id', 
                    \Modules\PkgFormation\Models\Filiere::class,
                    "id", 
                    "id",
                    $filieres
                );
            



    }


    /**
     * Crée une nouvelle instance de microCompetence.
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
    public function getMicroCompetenceStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function startFormation(int $microCompetenceId)
    {
        $microCompetence = $this->find($microCompetenceId);
        if (!$microCompetence) {
            return false; 
        }
        $value =  $microCompetence->save();
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
            'table' => 'PkgCompetences::microCompetence._table',
            default => 'PkgCompetences::microCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('microCompetence_view_type', $default_view_type);
        $microCompetence_viewType = $this->viewState->get('microCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('microCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.microCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.microCompetence.visible");
        }
        
        // Récupération des données
        $microCompetences_data = $this->paginate($params);
        $microCompetences_stats = $this->getmicroCompetenceStats();
        $microCompetences_total = $this->count();
        $microCompetences_filters = $this->getFieldsFilterable();
        $microCompetence_instance = $this->createInstance();
        $microCompetence_viewTypes = $this->getViewTypes();
        $microCompetence_partialViewName = $this->getPartialViewName($microCompetence_viewType);
        $microCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.microCompetence.stats', $microCompetences_stats);
    
        $microCompetences_permissions = [
            'startFormation-microCompetence' => Auth::user()->can('startFormation-microCompetence'),           
            
            'edit-microCompetence' => Auth::user()->can('edit-microCompetence'),
            'destroy-microCompetence' => Auth::user()->can('destroy-microCompetence'),
            'show-microCompetence' => Auth::user()->can('show-microCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $microCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($microCompetences_data as $item) {
                $microCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'microCompetence_viewTypes',
            'microCompetence_viewType',
            'microCompetences_data',
            'microCompetences_stats',
            'microCompetences_total',
            'microCompetences_filters',
            'microCompetence_instance',
            'microCompetence_title',
            'contextKey',
            'microCompetences_permissions',
            'microCompetences_permissionsByItem'
        );
    
        return [
            'microCompetences_data' => $microCompetences_data,
            'microCompetences_stats' => $microCompetences_stats,
            'microCompetences_total' => $microCompetences_total,
            'microCompetences_filters' => $microCompetences_filters,
            'microCompetence_instance' => $microCompetence_instance,
            'microCompetence_viewType' => $microCompetence_viewType,
            'microCompetence_viewTypes' => $microCompetence_viewTypes,
            'microCompetence_partialViewName' => $microCompetence_partialViewName,
            'contextKey' => $contextKey,
            'microCompetence_compact_value' => $compact_value,
            'microCompetences_permissions' => $microCompetences_permissions,
            'microCompetences_permissionsByItem' => $microCompetences_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $microCompetence_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $microCompetence_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($microCompetence_ids as $id) {
            $microCompetence = $this->find($id);
            $this->authorize('update', $microCompetence);
    
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
            'code',
            'titre',
            'competence_id',
            'lien',
            'UniteApprentissage'
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
    public function buildFieldMeta(MicroCompetence $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgCompetences\App\Requests\MicroCompetenceRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'micro_competence',
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

            case 'code':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'competence_id':
                 $values = (new \Modules\PkgCompetences\Services\CompetenceService())
                    ->getAllForSelect($e->competence)
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
            case 'lien':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'UniteApprentissage':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(MicroCompetence $e, array $changes): MicroCompetence
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
    public function formatDisplayValues(MicroCompetence $e, array $fields): array
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
                case 'code':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'titre':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'competence_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'competence'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'lien':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'lien'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'UniteApprentissage':
                    // fallback string simple
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
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
