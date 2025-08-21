<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Models\WorkflowTache;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe WorkflowTacheService pour gérer la persistance de l'entité WorkflowTache.
 */
class BaseWorkflowTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour workflowTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'titre',
        'description',
        'is_editable_only_by_formateur',
        'sys_color_id'
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
     * Constructeur de la classe WorkflowTacheService.
     */
    public function __construct()
    {
        parent::__construct(new WorkflowTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::workflowTache.plural');
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
            $workflowTache = $this->find($data['id']);
            $workflowTache->fill($data);
        } else {
            $workflowTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($workflowTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $workflowTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($workflowTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($workflowTache->id, $data);
            }
        }

        return $workflowTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('workflowTache');
        $this->fieldsFilterable = [];
        
            
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
            



    }


    /**
     * Crée une nouvelle instance de workflowTache.
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
    public function getWorkflowTacheStats(): array
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
            'table' => 'PkgRealisationTache::workflowTache._table',
            default => 'PkgRealisationTache::workflowTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('workflowTache_view_type', $default_view_type);
        $workflowTache_viewType = $this->viewState->get('workflowTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('workflowTache_view_type') === 'widgets') {
            $this->viewState->set("scope.workflowTache.visible", 1);
        }else{
            $this->viewState->remove("scope.workflowTache.visible");
        }
        
        // Récupération des données
        $workflowTaches_data = $this->paginate($params);
        $workflowTaches_stats = $this->getworkflowTacheStats();
        $workflowTaches_total = $this->count();
        $workflowTaches_filters = $this->getFieldsFilterable();
        $workflowTache_instance = $this->createInstance();
        $workflowTache_viewTypes = $this->getViewTypes();
        $workflowTache_partialViewName = $this->getPartialViewName($workflowTache_viewType);
        $workflowTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.workflowTache.stats', $workflowTaches_stats);
    
        $workflowTaches_permissions = [

            'edit-workflowTache' => Auth::user()->can('edit-workflowTache'),
            'destroy-workflowTache' => Auth::user()->can('destroy-workflowTache'),
            'show-workflowTache' => Auth::user()->can('show-workflowTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $workflowTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($workflowTaches_data as $item) {
                $workflowTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'workflowTache_viewTypes',
            'workflowTache_viewType',
            'workflowTaches_data',
            'workflowTaches_stats',
            'workflowTaches_total',
            'workflowTaches_filters',
            'workflowTache_instance',
            'workflowTache_title',
            'contextKey',
            'workflowTaches_permissions',
            'workflowTaches_permissionsByItem'
        );
    
        return [
            'workflowTaches_data' => $workflowTaches_data,
            'workflowTaches_stats' => $workflowTaches_stats,
            'workflowTaches_total' => $workflowTaches_total,
            'workflowTaches_filters' => $workflowTaches_filters,
            'workflowTache_instance' => $workflowTache_instance,
            'workflowTache_viewType' => $workflowTache_viewType,
            'workflowTache_viewTypes' => $workflowTache_viewTypes,
            'workflowTache_partialViewName' => $workflowTache_partialViewName,
            'contextKey' => $contextKey,
            'workflowTache_compact_value' => $compact_value,
            'workflowTaches_permissions' => $workflowTaches_permissions,
            'workflowTaches_permissionsByItem' => $workflowTaches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $workflowTache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $workflowTache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($workflowTache_ids as $id) {
            $workflowTache = $this->find($id);
            $this->authorize('update', $workflowTache);
    
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
    public function getFieldsEditable(): array
    {
        return [
            'ordre',
            'code',
            'titre',
            'sys_color_id'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(WorkflowTache $e, string $field): array
    {
        $meta = [
            'entity'         => 'workflow_tache',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationTache\App\Requests\WorkflowTacheRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'ordre':
                return $this->computeFieldMeta($e, $field, $meta, 'number', $validationRules);

            case 'code':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            case 'titre':
                return $this->computeFieldMeta($e, $field, $meta, 'string', $validationRules);
            case 'sys_color_id':
                 $values = (new \Modules\Core\Services\SysColorService())
                    ->getAllForSelect($e->sysColor)
                    ->map(fn($entity) => [
                        'value' => (int) $entity->id,
                        'label' => (string) $entity,
                    ])
                    ->toArray();

                return $this->computeFieldMeta($e, $field, $meta, 'select', $validationRules, [
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
    public function applyInlinePatch(WorkflowTache $e, array $changes): WorkflowTache
    {
        $allowed = $this->getFieldsEditable();
        $filtered = Arr::only($changes, $allowed);

        if (empty($filtered)) {
            abort(422, 'Aucun champ autorisé.');
        }

        $rules = [];
        foreach ($filtered as $field => $value) {
            $meta = $this->buildFieldMeta($e, $field);
            $rules[$field] = $meta['validation'] ?? ['nullable'];
        }
        Validator::make($filtered, $rules)->validate();

        $e->fill($filtered);
        $e->save();
        $e->refresh();
        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(WorkflowTache $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'ordre':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'code':
                    // fallback string simple
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'titre':
                    // fallback string simple
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'sys_color_id':
                    // fallback string simple
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
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
