<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EtatRealisationTacheService pour gérer la persistance de l'entité EtatRealisationTache.
 */
class BaseEtatRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'workflow_tache_id',
        'sys_color_id',
        'is_editable_only_by_formateur',
        'reference',
        'formateur_id',
        'description'
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
     * Constructeur de la classe EtatRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::etatRealisationTache.plural');
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
            $etatRealisationTache = $this->find($data['id']);
            $etatRealisationTache->fill($data);
        } else {
            $etatRealisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatRealisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatRealisationTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatRealisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatRealisationTache->id, $data);
            }
        }

        return $etatRealisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationTache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('workflow_tache_id', $scopeVariables)) {


                    $workflowTacheService = new \Modules\PkgRealisationTache\Services\WorkflowTacheService();
                    $workflowTacheIds = $this->getAvailableFilterValues('workflow_tache_id');
                    $workflowTaches = $workflowTacheService->getByIds($workflowTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::workflowTache.plural"), 
                        'workflow_tache_id', 
                        \Modules\PkgRealisationTache\Models\WorkflowTache::class, 
                        'code',
                        $workflowTaches
                    );
                }
            
            
                if (!array_key_exists('sys_color_id', $scopeVariables)) {


                    $sysColorService = new \Modules\Core\Services\SysColorService();
                    $sysColorIds = $this->getAvailableFilterValues('sys_color_id');
                    $sysColors = $sysColorService->getByIds($sysColorIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysColor.plural"), 
                        'sys_color_id', 
                        \Modules\Core\Models\SysColor::class, 
                        'name',
                        $sysColors
                    );
                }
            
            
                if (!array_key_exists('formateur_id', $scopeVariables)) {


                    $formateurService = new \Modules\PkgFormation\Services\FormateurService();
                    $formateurIds = $this->getAvailableFilterValues('formateur_id');
                    $formateurs = $formateurService->getByIds($formateurIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::formateur.plural"), 
                        'formateur_id', 
                        \Modules\PkgFormation\Models\Formateur::class, 
                        'nom',
                        $formateurs
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de etatRealisationTache.
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
    public function getEtatRealisationTacheStats(): array
    {

        $stats = $this->initStats();

        // Ajouter les statistiques du propriétaire
        //$contexteState = $this->getContextState();
        // if ($contexteState !== null) {
        //     $stats[] = $contexteState;
        // }
        

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
            'table' => 'PkgRealisationTache::etatRealisationTache._table',
            default => 'PkgRealisationTache::etatRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationTache_view_type', $default_view_type);
        $etatRealisationTache_viewType = $this->viewState->get('etatRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationTache.visible");
        }
        
        // Récupération des données
        $etatRealisationTaches_data = $this->paginate($params);
        $etatRealisationTaches_stats = $this->getetatRealisationTacheStats();
        $etatRealisationTaches_total = $this->count();
        $etatRealisationTaches_filters = $this->getFieldsFilterable();
        $etatRealisationTache_instance = $this->createInstance();
        $etatRealisationTache_viewTypes = $this->getViewTypes();
        $etatRealisationTache_partialViewName = $this->getPartialViewName($etatRealisationTache_viewType);
        $etatRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationTache.stats', $etatRealisationTaches_stats);
    
        $etatRealisationTaches_permissions = [

            'edit-etatRealisationTache' => Auth::user()->can('edit-etatRealisationTache'),
            'destroy-etatRealisationTache' => Auth::user()->can('destroy-etatRealisationTache'),
            'show-etatRealisationTache' => Auth::user()->can('show-etatRealisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationTaches_data as $item) {
                $etatRealisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationTache_viewTypes',
            'etatRealisationTache_viewType',
            'etatRealisationTaches_data',
            'etatRealisationTaches_stats',
            'etatRealisationTaches_total',
            'etatRealisationTaches_filters',
            'etatRealisationTache_instance',
            'etatRealisationTache_title',
            'contextKey',
            'etatRealisationTaches_permissions',
            'etatRealisationTaches_permissionsByItem'
        );
    
        return [
            'etatRealisationTaches_data' => $etatRealisationTaches_data,
            'etatRealisationTaches_stats' => $etatRealisationTaches_stats,
            'etatRealisationTaches_total' => $etatRealisationTaches_total,
            'etatRealisationTaches_filters' => $etatRealisationTaches_filters,
            'etatRealisationTache_instance' => $etatRealisationTache_instance,
            'etatRealisationTache_viewType' => $etatRealisationTache_viewType,
            'etatRealisationTache_viewTypes' => $etatRealisationTache_viewTypes,
            'etatRealisationTache_partialViewName' => $etatRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationTache_compact_value' => $compact_value,
            'etatRealisationTaches_permissions' => $etatRealisationTaches_permissions,
            'etatRealisationTaches_permissionsByItem' => $etatRealisationTaches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatRealisationTache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatRealisationTache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatRealisationTache_ids as $id) {
            $etatRealisationTache = $this->find($id);
            $this->authorize('update', $etatRealisationTache);
    
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
            'nom',
            'workflow_tache_id',
            'sys_color_id',
            'formateur_id'
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
    public function buildFieldMeta(EtatRealisationTache $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationTache\App\Requests\EtatRealisationTacheRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'etat_realisation_tache',
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

            case 'nom':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'workflow_tache_id':
                 $values = (new \Modules\PkgRealisationTache\Services\WorkflowTacheService())
                    ->getAllForSelect($e->workflowTache)
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
            case 'sys_color_id':
                 $values = (new \Modules\Core\Services\SysColorService())
                    ->getAllForSelect($e->sysColor)
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
            case 'formateur_id':
                 $values = (new \Modules\PkgFormation\Services\FormateurService())
                    ->getAllForSelect($e->formateur)
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
    public function applyInlinePatch(EtatRealisationTache $e, array $changes): EtatRealisationTache
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
    public function formatDisplayValues(EtatRealisationTache $e, array $fields): array
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
                case 'nom':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'workflow_tache_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'workflowTache'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'sys_color_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'couleur',
                        'relationName' => 'sysColor'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'formateur_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'formateur'
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
