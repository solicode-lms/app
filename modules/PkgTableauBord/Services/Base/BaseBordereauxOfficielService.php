<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgTableauBord\Models\BordereauxOfficiel;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe BordereauxOfficielService pour gérer la persistance de l'entité BordereauxOfficiel.
 */
class BaseBordereauxOfficielService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour bordereauxOfficiels.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'module_id',
        'groupe_id',
        'annee_formation_id',
        'nb_apprenants',
        'taux_conformite_calcul',
        'statut',
        'date_scellement',
        'hash_signature',
        'genere_par_user_id'
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
     * Constructeur de la classe BordereauxOfficielService.
     */
    public function __construct()
    {
        parent::__construct(new BordereauxOfficiel());
        $this->fieldsFilterable = [];
        $this->title = __('PkgTableauBord::bordereauxOfficiel.plural');
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
            $bordereauxOfficiel = $this->find($data['id']);
            $bordereauxOfficiel->fill($data);
        } else {
            $bordereauxOfficiel = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($bordereauxOfficiel->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $bordereauxOfficiel->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($bordereauxOfficiel->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($bordereauxOfficiel->id, $data);
            }
        }

        return $bordereauxOfficiel;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('bordereauxOfficiel');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('module_id', $scopeVariables)) {


                    $moduleService = new \Modules\PkgFormation\Services\ModuleService();
                    $moduleIds = $this->getAvailableFilterValues('module_id');
                    $modules = $moduleService->getByIds($moduleIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::module.plural"), 
                        'module_id', 
                        \Modules\PkgFormation\Models\Module::class, 
                        'code',
                        $modules
                    );
                }
            
            
                if (!array_key_exists('groupe_id', $scopeVariables)) {


                    $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                    $groupeIds = $this->getAvailableFilterValues('groupe_id');
                    $groupes = $groupeService->getByIds($groupeIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::groupe.plural"), 
                        'groupe_id', 
                        \Modules\PkgApprenants\Models\Groupe::class, 
                        'code',
                        $groupes
                    );
                }
            
            
                if (!array_key_exists('annee_formation_id', $scopeVariables)) {


                    $anneeFormationService = new \Modules\PkgFormation\Services\AnneeFormationService();
                    $anneeFormationIds = $this->getAvailableFilterValues('annee_formation_id');
                    $anneeFormations = $anneeFormationService->getByIds($anneeFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::anneeFormation.plural"), 
                        'annee_formation_id', 
                        \Modules\PkgFormation\Models\AnneeFormation::class, 
                        'titre',
                        $anneeFormations
                    );
                }
            
            
                if (!array_key_exists('genere_par_user_id', $scopeVariables)) {


                    $userService = new \Modules\PkgAutorisation\Services\UserService();
                    $userIds = $this->getAvailableFilterValues('genere_par_user_id');
                    $users = $userService->getByIds($userIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgAutorisation::user.plural"), 
                        'genere_par_user_id', 
                        \Modules\PkgAutorisation\Models\User::class, 
                        'name',
                        $users
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de bordereauxOfficiel.
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
    public function getBordereauxOfficielStats(): array
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
            'table' => 'PkgTableauBord::bordereauxOfficiel._table',
            default => 'PkgTableauBord::bordereauxOfficiel._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('bordereauxOfficiel_view_type', $default_view_type);
        $bordereauxOfficiel_viewType = $this->viewState->get('bordereauxOfficiel_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('bordereauxOfficiel_view_type') === 'widgets') {
            $this->viewState->set("scope.bordereauxOfficiel.visible", 1);
        }else{
            $this->viewState->remove("scope.bordereauxOfficiel.visible");
        }
        
        // Récupération des données
        $bordereauxOfficiels_data = $this->paginate($params);
        $bordereauxOfficiels_stats = $this->getbordereauxOfficielStats();
        $bordereauxOfficiels_total = $this->count();
        $bordereauxOfficiels_filters = $this->getFieldsFilterable();
        $bordereauxOfficiel_instance = $this->createInstance();
        $bordereauxOfficiel_viewTypes = $this->getViewTypes();
        $bordereauxOfficiel_partialViewName = $this->getPartialViewName($bordereauxOfficiel_viewType);
        $bordereauxOfficiel_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.bordereauxOfficiel.stats', $bordereauxOfficiels_stats);
    
        $bordereauxOfficiels_permissions = [

            'edit-bordereauxOfficiel' => Auth::user()->can('edit-bordereauxOfficiel'),
            'destroy-bordereauxOfficiel' => Auth::user()->can('destroy-bordereauxOfficiel'),
            'show-bordereauxOfficiel' => Auth::user()->can('show-bordereauxOfficiel'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $bordereauxOfficiels_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($bordereauxOfficiels_data as $item) {
                $bordereauxOfficiels_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        $scopeVariables = $this->viewState->getScopeVariablesTitles('bordereauxOfficiel');

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'bordereauxOfficiel_viewTypes',
            'bordereauxOfficiel_viewType',
            'bordereauxOfficiels_data',
            'bordereauxOfficiels_stats',
            'bordereauxOfficiels_total',
            'bordereauxOfficiels_filters',
            'bordereauxOfficiel_instance',
            'bordereauxOfficiel_title',
            'contextKey',
            'bordereauxOfficiels_permissions',
            'bordereauxOfficiels_permissionsByItem',
            'scopeVariables'
        );
    
        return [
            'bordereauxOfficiels_data' => $bordereauxOfficiels_data,
            'bordereauxOfficiels_stats' => $bordereauxOfficiels_stats,
            'bordereauxOfficiels_total' => $bordereauxOfficiels_total,
            'bordereauxOfficiels_filters' => $bordereauxOfficiels_filters,
            'bordereauxOfficiel_instance' => $bordereauxOfficiel_instance,
            'bordereauxOfficiel_viewType' => $bordereauxOfficiel_viewType,
            'bordereauxOfficiel_viewTypes' => $bordereauxOfficiel_viewTypes,
            'bordereauxOfficiel_partialViewName' => $bordereauxOfficiel_partialViewName,
            'contextKey' => $contextKey,
            'bordereauxOfficiel_compact_value' => $compact_value,
            'bordereauxOfficiels_permissions' => $bordereauxOfficiels_permissions,
            'bordereauxOfficiels_permissionsByItem' => $bordereauxOfficiels_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $bordereauxOfficiel_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $bordereauxOfficiel_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($bordereauxOfficiel_ids as $id) {
            $bordereauxOfficiel = $this->find($id);
            $this->authorize('update', $bordereauxOfficiel);
    
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
            'module_id',
            'groupe_id',
            'annee_formation_id',
            'statut',
            'genere_par_user_id'
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
    public function buildFieldMeta(BordereauxOfficiel $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgTableauBord\App\Requests\BordereauxOfficielRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'bordereaux_officiel',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
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
            case 'groupe_id':
                 $values = (new \Modules\PkgApprenants\Services\GroupeService())
                    ->getAllForSelect($e->groupe)
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
            case 'annee_formation_id':
                 $values = (new \Modules\PkgFormation\Services\AnneeFormationService())
                    ->getAllForSelect($e->anneeFormation)
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
            case 'statut':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'genere_par_user_id':
                 $values = (new \Modules\PkgAutorisation\Services\UserService())
                    ->getAllForSelect($e->genereParUser)
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
    public function applyInlinePatch(BordereauxOfficiel $e, array $changes): BordereauxOfficiel
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
    public function formatDisplayValues(BordereauxOfficiel $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'module_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'module'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'groupe_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'groupe'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'annee_formation_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'anneeFormation'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'statut':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'genere_par_user_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'genereParUser'
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
