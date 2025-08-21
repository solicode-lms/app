<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

/**
 * Classe HistoriqueRealisationTacheService pour gérer la persistance de l'entité HistoriqueRealisationTache.
 */
class BaseHistoriqueRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour historiqueRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'changement',
        'dateModification',
        'realisation_tache_id',
        'user_id',
        'isFeedback'
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
     * Constructeur de la classe HistoriqueRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new HistoriqueRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::historiqueRealisationTache.plural');
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
            $historiqueRealisationTache = $this->find($data['id']);
            $historiqueRealisationTache->fill($data);
        } else {
            $historiqueRealisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($historiqueRealisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $historiqueRealisationTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($historiqueRealisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($historiqueRealisationTache->id, $data);
            }
        }

        return $historiqueRealisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('historiqueRealisationTache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('realisation_tache_id', $scopeVariables)) {


                    $realisationTacheService = new \Modules\PkgRealisationTache\Services\RealisationTacheService();
                    $realisationTacheIds = $this->getAvailableFilterValues('realisation_tache_id');
                    $realisationTaches = $realisationTacheService->getByIds($realisationTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::realisationTache.plural"), 
                        'realisation_tache_id', 
                        \Modules\PkgRealisationTache\Models\RealisationTache::class, 
                        'id',
                        $realisationTaches
                    );
                }
            
            
                if (!array_key_exists('user_id', $scopeVariables)) {


                    $userService = new \Modules\PkgAutorisation\Services\UserService();
                    $userIds = $this->getAvailableFilterValues('user_id');
                    $users = $userService->getByIds($userIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgAutorisation::user.plural"), 
                        'user_id', 
                        \Modules\PkgAutorisation\Models\User::class, 
                        'name',
                        $users
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de historiqueRealisationTache.
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
    public function getHistoriqueRealisationTacheStats(): array
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
            'table' => 'PkgRealisationTache::historiqueRealisationTache._table',
            default => 'PkgRealisationTache::historiqueRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('historiqueRealisationTache_view_type', $default_view_type);
        $historiqueRealisationTache_viewType = $this->viewState->get('historiqueRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('historiqueRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.historiqueRealisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.historiqueRealisationTache.visible");
        }
        
        // Récupération des données
        $historiqueRealisationTaches_data = $this->paginate($params);
        $historiqueRealisationTaches_stats = $this->gethistoriqueRealisationTacheStats();
        $historiqueRealisationTaches_total = $this->count();
        $historiqueRealisationTaches_filters = $this->getFieldsFilterable();
        $historiqueRealisationTache_instance = $this->createInstance();
        $historiqueRealisationTache_viewTypes = $this->getViewTypes();
        $historiqueRealisationTache_partialViewName = $this->getPartialViewName($historiqueRealisationTache_viewType);
        $historiqueRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.historiqueRealisationTache.stats', $historiqueRealisationTaches_stats);
    
        $historiqueRealisationTaches_permissions = [

            'edit-historiqueRealisationTache' => Auth::user()->can('edit-historiqueRealisationTache'),
            'destroy-historiqueRealisationTache' => Auth::user()->can('destroy-historiqueRealisationTache'),
            'show-historiqueRealisationTache' => Auth::user()->can('show-historiqueRealisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $historiqueRealisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($historiqueRealisationTaches_data as $item) {
                $historiqueRealisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'historiqueRealisationTache_viewTypes',
            'historiqueRealisationTache_viewType',
            'historiqueRealisationTaches_data',
            'historiqueRealisationTaches_stats',
            'historiqueRealisationTaches_total',
            'historiqueRealisationTaches_filters',
            'historiqueRealisationTache_instance',
            'historiqueRealisationTache_title',
            'contextKey',
            'historiqueRealisationTaches_permissions',
            'historiqueRealisationTaches_permissionsByItem'
        );
    
        return [
            'historiqueRealisationTaches_data' => $historiqueRealisationTaches_data,
            'historiqueRealisationTaches_stats' => $historiqueRealisationTaches_stats,
            'historiqueRealisationTaches_total' => $historiqueRealisationTaches_total,
            'historiqueRealisationTaches_filters' => $historiqueRealisationTaches_filters,
            'historiqueRealisationTache_instance' => $historiqueRealisationTache_instance,
            'historiqueRealisationTache_viewType' => $historiqueRealisationTache_viewType,
            'historiqueRealisationTache_viewTypes' => $historiqueRealisationTache_viewTypes,
            'historiqueRealisationTache_partialViewName' => $historiqueRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'historiqueRealisationTache_compact_value' => $compact_value,
            'historiqueRealisationTaches_permissions' => $historiqueRealisationTaches_permissions,
            'historiqueRealisationTaches_permissionsByItem' => $historiqueRealisationTaches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $historiqueRealisationTache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $historiqueRealisationTache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($historiqueRealisationTache_ids as $id) {
            $historiqueRealisationTache = $this->find($id);
            $this->authorize('update', $historiqueRealisationTache);
    
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
            'changement',
            'dateModification',
            'user_id',
            'updated_at'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(HistoriqueRealisationTache $e, string $field): array
    {
        $meta = [
            'entity'         => 'historique_realisation_tache',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
        ];

        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationTache\App\Requests\HistoriqueRealisationTacheRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }
       switch ($field) {
            case 'changement':
                return $this->computeFieldMeta($e, $field, $meta, 'text', $validationRules);

            case 'dateModification':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            case 'user_id':
                 $values = (new \Modules\PkgAutorisation\Services\UserService())
                    ->getAllForSelect($e->user)
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
            case 'updated_at':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(HistoriqueRealisationTache $e, array $changes): HistoriqueRealisationTache
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
    public function formatDisplayValues(HistoriqueRealisationTache $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'changement':
                    $html = view('Core::fields_by_type.text', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'html'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'dateModification':
                    // Vue custom définie pour ce champ
                    $html = view('PkgRealisationTache::historiqueRealisationTache.custom.fields.dateModification', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'user_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'user'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'updated_at':
                    $html = view('Core::fields_by_type.date', [
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
