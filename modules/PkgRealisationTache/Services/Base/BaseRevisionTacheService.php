<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Models\RevisionTache;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe RevisionTacheService pour gérer la persistance de l'entité RevisionTache.
 */
class BaseRevisionTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour revisionTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'realisation_tache_id',
        'tentative',
        'date_demande',
        'message_formateur',
        'date_soumission',
        'date_validation',
        'est_valide'
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
     * Constructeur de la classe RevisionTacheService.
     */
    public function __construct()
    {
        parent::__construct(new RevisionTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::revisionTache.plural');
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
            $revisionTache = $this->find($data['id']);
            $revisionTache->fill($data);
        } else {
            $revisionTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($revisionTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $revisionTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($revisionTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($revisionTache->id, $data);
            }
        }

        return $revisionTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('revisionTache');
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
            



    }


    /**
     * Crée une nouvelle instance de revisionTache.
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
    public function getRevisionTacheStats(): array
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
            'table' => 'PkgRealisationTache::revisionTache._table',
            default => 'PkgRealisationTache::revisionTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('revisionTache_view_type', $default_view_type);
        $revisionTache_viewType = $this->viewState->get('revisionTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('revisionTache_view_type') === 'widgets') {
            $this->viewState->set("scope.revisionTache.visible", 1);
        }else{
            $this->viewState->remove("scope.revisionTache.visible");
        }
        
        // Récupération des données
        $revisionTaches_data = $this->paginate($params);
        $revisionTaches_stats = $this->getrevisionTacheStats();
        $revisionTaches_total = $this->count();
        $revisionTaches_filters = $this->getFieldsFilterable();
        $revisionTache_instance = $this->createInstance();
        $revisionTache_viewTypes = $this->getViewTypes();
        $revisionTache_partialViewName = $this->getPartialViewName($revisionTache_viewType);
        $revisionTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.revisionTache.stats', $revisionTaches_stats);
    
        $revisionTaches_permissions = [

            'edit-revisionTache' => Auth::user()->can('edit-revisionTache'),
            'destroy-revisionTache' => Auth::user()->can('destroy-revisionTache'),
            'show-revisionTache' => Auth::user()->can('show-revisionTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $revisionTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($revisionTaches_data as $item) {
                $revisionTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        $scopeVariables = $this->viewState->getScopeVariablesTitles('revisionTache');

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'revisionTache_viewTypes',
            'revisionTache_viewType',
            'revisionTaches_data',
            'revisionTaches_stats',
            'revisionTaches_total',
            'revisionTaches_filters',
            'revisionTache_instance',
            'revisionTache_title',
            'contextKey',
            'revisionTaches_permissions',
            'revisionTaches_permissionsByItem',
            'scopeVariables'
        );
    
        return [
            'revisionTaches_data' => $revisionTaches_data,
            'revisionTaches_stats' => $revisionTaches_stats,
            'revisionTaches_total' => $revisionTaches_total,
            'revisionTaches_filters' => $revisionTaches_filters,
            'revisionTache_instance' => $revisionTache_instance,
            'revisionTache_viewType' => $revisionTache_viewType,
            'revisionTache_viewTypes' => $revisionTache_viewTypes,
            'revisionTache_partialViewName' => $revisionTache_partialViewName,
            'contextKey' => $contextKey,
            'revisionTache_compact_value' => $compact_value,
            'revisionTaches_permissions' => $revisionTaches_permissions,
            'revisionTaches_permissionsByItem' => $revisionTaches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $revisionTache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $revisionTache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($revisionTache_ids as $id) {
            $revisionTache = $this->find($id);
            $this->authorize('update', $revisionTache);
    
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
            'date_validation'
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
    public function buildFieldMeta(RevisionTache $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationTache\App\Requests\RevisionTacheRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'revision_tache',
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
            case 'date_validation':
                return $this->computeFieldMeta($e, $field, $meta, 'date', $validationRules);
            
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RevisionTache $e, array $changes): RevisionTache
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
    public function formatDisplayValues(RevisionTache $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'realisation_tache_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'realisationTache'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;



                case 'date_validation':
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
