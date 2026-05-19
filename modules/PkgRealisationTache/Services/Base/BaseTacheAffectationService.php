<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Models\TacheAffectation;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe TacheAffectationService pour gérer la persistance de l'entité TacheAffectation.
 */
class BaseTacheAffectationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour tacheAffectations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'tache_id',
        'affectation_projet_id',
        'pourcentage_realisation_cache',
        'apprenant_live_coding_cache',
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
     * Constructeur de la classe TacheAffectationService.
     */
    public function __construct()
    {
        parent::__construct(new TacheAffectation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::tacheAffectation.plural');
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
            $tacheAffectation = $this->find($data['id']);
            $tacheAffectation->fill($data);
        } else {
            $tacheAffectation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($tacheAffectation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $tacheAffectation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($tacheAffectation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($tacheAffectation->id, $data);
            }
        }

        return $tacheAffectation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('tacheAffectation');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('tache_id', $scopeVariables)) {


                    $tacheService = new \Modules\PkgCreationTache\Services\TacheService();
                    $tacheIds = $this->getAvailableFilterValues('tache_id');
                    $taches = $tacheService->getByIds($tacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationTache::tache.plural"), 
                        'tache_id', 
                        \Modules\PkgCreationTache\Models\Tache::class, 
                        'titre',
                        $taches
                    );
                }
            
            
                if (!array_key_exists('affectation_projet_id', $scopeVariables)) {


                    $affectationProjetService = new \Modules\PkgRealisationProjets\Services\AffectationProjetService();
                    $affectationProjetIds = $this->getAvailableFilterValues('affectation_projet_id');
                    $affectationProjets = $affectationProjetService->getByIds($affectationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationProjets::affectationProjet.plural"), 
                        'affectation_projet_id', 
                        \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 
                        'id',
                        $affectationProjets
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de tacheAffectation.
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
    public function getTacheAffectationStats(): array
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
            'table' => 'PkgRealisationTache::tacheAffectation._table',
            default => 'PkgRealisationTache::tacheAffectation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('tacheAffectation_view_type', $default_view_type);
        $tacheAffectation_viewType = $this->viewState->get('tacheAffectation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('tacheAffectation_view_type') === 'widgets') {
            $this->viewState->set("scope.tacheAffectation.visible", 1);
        }else{
            $this->viewState->remove("scope.tacheAffectation.visible");
        }
        
        // Récupération des données
        $tacheAffectations_data = $this->paginate($params);
        $tacheAffectations_stats = $this->gettacheAffectationStats();
        $tacheAffectations_total = $this->count();
        $tacheAffectations_filters = $this->getFieldsFilterable();
        $tacheAffectation_instance = $this->createInstance();
        $tacheAffectation_viewTypes = $this->getViewTypes();
        $tacheAffectation_partialViewName = $this->getPartialViewName($tacheAffectation_viewType);
        $tacheAffectation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.tacheAffectation.stats', $tacheAffectations_stats);
    
        $tacheAffectations_permissions = [

            'edit-tacheAffectation' => Auth::user()->can('edit-tacheAffectation'),
            'destroy-tacheAffectation' => Auth::user()->can('destroy-tacheAffectation'),
            'show-tacheAffectation' => Auth::user()->can('show-tacheAffectation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $tacheAffectations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($tacheAffectations_data as $item) {
                $tacheAffectations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'tacheAffectation_viewTypes',
            'tacheAffectation_viewType',
            'tacheAffectations_data',
            'tacheAffectations_stats',
            'tacheAffectations_total',
            'tacheAffectations_filters',
            'tacheAffectation_instance',
            'tacheAffectation_title',
            'contextKey',
            'tacheAffectations_permissions',
            'tacheAffectations_permissionsByItem'
        );
    
        return [
            'tacheAffectations_data' => $tacheAffectations_data,
            'tacheAffectations_stats' => $tacheAffectations_stats,
            'tacheAffectations_total' => $tacheAffectations_total,
            'tacheAffectations_filters' => $tacheAffectations_filters,
            'tacheAffectation_instance' => $tacheAffectation_instance,
            'tacheAffectation_viewType' => $tacheAffectation_viewType,
            'tacheAffectation_viewTypes' => $tacheAffectation_viewTypes,
            'tacheAffectation_partialViewName' => $tacheAffectation_partialViewName,
            'contextKey' => $contextKey,
            'tacheAffectation_compact_value' => $compact_value,
            'tacheAffectations_permissions' => $tacheAffectations_permissions,
            'tacheAffectations_permissionsByItem' => $tacheAffectations_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $tacheAffectation_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $tacheAffectation_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($tacheAffectation_ids as $id) {
            $tacheAffectation = $this->find($id);
            $this->authorize('update', $tacheAffectation);
    
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
            'tache_id',
            'affectation_projet_id',
            'pourcentage_realisation_cache'
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
    public function buildFieldMeta(TacheAffectation $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationTache\App\Requests\TacheAffectationRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'tache_affectation',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'tache_id':
                 $values = (new \Modules\PkgCreationTache\Services\TacheService())
                    ->getAllForSelect($e->tache)
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
            case 'affectation_projet_id':
                 $values = (new \Modules\PkgRealisationProjets\Services\AffectationProjetService())
                    ->getAllForSelect($e->affectationProjet)
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
            case 'pourcentage_realisation_cache':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(TacheAffectation $e, array $changes): TacheAffectation
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
    public function formatDisplayValues(TacheAffectation $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'tache_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'tache'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'affectation_projet_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'affectationProjet'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'pourcentage_realisation_cache':
                    $html = view('Core::fields_by_type.integer', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'progression'
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
