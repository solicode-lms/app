<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationTache;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EvaluationRealisationTacheService pour gérer la persistance de l'entité EvaluationRealisationTache.
 */
class BaseEvaluationRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour evaluationRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'realisation_tache_id',
        'evaluateur_id',
        'note',
        'message',
        'evaluation_realisation_projet_id',
        'reference'
    ];



    public function editableFieldsByRoles(): array
    {
        return [
          'realisation_tache_id' => ['admin'],
          'evaluateur_id' => ['admin'],
          'evaluation_realisation_projet_id' => ['admin']
        
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
     * Constructeur de la classe EvaluationRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new EvaluationRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgEvaluateurs::evaluationRealisationTache.plural');
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
            $evaluationRealisationTache = $this->find($data['id']);
            $evaluationRealisationTache->fill($data);
        } else {
            $evaluationRealisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($evaluationRealisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $evaluationRealisationTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($evaluationRealisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($evaluationRealisationTache->id, $data);
            }
        }

        return $evaluationRealisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('evaluationRealisationTache');
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
            
            
                if (!array_key_exists('evaluateur_id', $scopeVariables)) {


                    $evaluateurService = new \Modules\PkgEvaluateurs\Services\EvaluateurService();
                    $evaluateurIds = $this->getAvailableFilterValues('evaluateur_id');
                    $evaluateurs = $evaluateurService->getByIds($evaluateurIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgEvaluateurs::evaluateur.plural"), 
                        'evaluateur_id', 
                        \Modules\PkgEvaluateurs\Models\Evaluateur::class, 
                        'nom',
                        $evaluateurs
                    );
                }
            
            
                if (!array_key_exists('evaluation_realisation_projet_id', $scopeVariables)) {


                    $evaluationRealisationProjetService = new \Modules\PkgEvaluateurs\Services\EvaluationRealisationProjetService();
                    $evaluationRealisationProjetIds = $this->getAvailableFilterValues('evaluation_realisation_projet_id');
                    $evaluationRealisationProjets = $evaluationRealisationProjetService->getByIds($evaluationRealisationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgEvaluateurs::evaluationRealisationProjet.plural"), 
                        'evaluation_realisation_projet_id', 
                        \Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet::class, 
                        'id',
                        $evaluationRealisationProjets
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de evaluationRealisationTache.
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
    public function getEvaluationRealisationTacheStats(): array
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
            'table' => 'PkgEvaluateurs::evaluationRealisationTache._table',
            default => 'PkgEvaluateurs::evaluationRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('evaluationRealisationTache_view_type', $default_view_type);
        $evaluationRealisationTache_viewType = $this->viewState->get('evaluationRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('evaluationRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.evaluationRealisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.evaluationRealisationTache.visible");
        }
        
        // Récupération des données
        $evaluationRealisationTaches_data = $this->paginate($params);
        $evaluationRealisationTaches_stats = $this->getevaluationRealisationTacheStats();
        $evaluationRealisationTaches_total = $this->count();
        $evaluationRealisationTaches_filters = $this->getFieldsFilterable();
        $evaluationRealisationTache_instance = $this->createInstance();
        $evaluationRealisationTache_viewTypes = $this->getViewTypes();
        $evaluationRealisationTache_partialViewName = $this->getPartialViewName($evaluationRealisationTache_viewType);
        $evaluationRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.evaluationRealisationTache.stats', $evaluationRealisationTaches_stats);
    
        $evaluationRealisationTaches_permissions = [

            'edit-evaluationRealisationTache' => Auth::user()->can('edit-evaluationRealisationTache'),
            'destroy-evaluationRealisationTache' => Auth::user()->can('destroy-evaluationRealisationTache'),
            'show-evaluationRealisationTache' => Auth::user()->can('show-evaluationRealisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $evaluationRealisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($evaluationRealisationTaches_data as $item) {
                $evaluationRealisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'evaluationRealisationTache_viewTypes',
            'evaluationRealisationTache_viewType',
            'evaluationRealisationTaches_data',
            'evaluationRealisationTaches_stats',
            'evaluationRealisationTaches_total',
            'evaluationRealisationTaches_filters',
            'evaluationRealisationTache_instance',
            'evaluationRealisationTache_title',
            'contextKey',
            'evaluationRealisationTaches_permissions',
            'evaluationRealisationTaches_permissionsByItem'
        );
    
        return [
            'evaluationRealisationTaches_data' => $evaluationRealisationTaches_data,
            'evaluationRealisationTaches_stats' => $evaluationRealisationTaches_stats,
            'evaluationRealisationTaches_total' => $evaluationRealisationTaches_total,
            'evaluationRealisationTaches_filters' => $evaluationRealisationTaches_filters,
            'evaluationRealisationTache_instance' => $evaluationRealisationTache_instance,
            'evaluationRealisationTache_viewType' => $evaluationRealisationTache_viewType,
            'evaluationRealisationTache_viewTypes' => $evaluationRealisationTache_viewTypes,
            'evaluationRealisationTache_partialViewName' => $evaluationRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'evaluationRealisationTache_compact_value' => $compact_value,
            'evaluationRealisationTaches_permissions' => $evaluationRealisationTaches_permissions,
            'evaluationRealisationTaches_permissionsByItem' => $evaluationRealisationTaches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $evaluationRealisationTache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $evaluationRealisationTache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($evaluationRealisationTache_ids as $id) {
            $evaluationRealisationTache = $this->find($id);
            $this->authorize('update', $evaluationRealisationTache);
    
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
            'realisation_tache_id',
            'note',
            'nombre_livrables'
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
    public function buildFieldMeta(EvaluationRealisationTache $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgEvaluateurs\App\Requests\EvaluationRealisationTacheRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'evaluation_realisation_tache',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'realisation_tache_id':
                 $values = (new \Modules\PkgRealisationTache\Services\RealisationTacheService())
                    ->getAllForSelect($e->realisationTache)
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
            case 'note':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'nombre_livrables':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EvaluationRealisationTache $e, array $changes): EvaluationRealisationTache
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
    public function formatDisplayValues(EvaluationRealisationTache $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'realisation_tache_id':
                    // Vue custom définie pour ce champ
                    $html = view('PkgEvaluateurs::evaluationRealisationTache.custom.fields.realisationTache', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'note':
                    // Vue custom définie pour ce champ
                    $html = view('PkgEvaluateurs::evaluationRealisationTache.custom.fields.note', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'nombre_livrables':
                    // Vue custom définie pour ce champ
                    $html = view('PkgEvaluateurs::evaluationRealisationTache.custom.fields.nombre_livrables', [
                        'entity' => $e
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
