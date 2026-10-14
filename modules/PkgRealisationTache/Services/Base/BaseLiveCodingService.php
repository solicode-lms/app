<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationTache\Models\LiveCoding;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe LiveCodingService pour gérer la persistance de l'entité LiveCoding.
 */
class BaseLiveCodingService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour liveCodings.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'realisation_tache_id',
        'tentative',
        'est_valide',
        'date_passage',
        'commentaire'
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
     * Constructeur de la classe LiveCodingService.
     */
    public function __construct()
    {
        parent::__construct(new LiveCoding());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::liveCoding.plural');
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
            $liveCoding = $this->find($data['id']);
            $liveCoding->fill($data);
        } else {
            $liveCoding = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($liveCoding->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $liveCoding->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($liveCoding->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($liveCoding->id, $data);
            }
        }

        return $liveCoding;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('liveCoding');
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
     * Crée une nouvelle instance de liveCoding.
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
    public function getLiveCodingStats(): array
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
            'table' => 'PkgRealisationTache::liveCoding._table',
            default => 'PkgRealisationTache::liveCoding._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('liveCoding_view_type', $default_view_type);
        $liveCoding_viewType = $this->viewState->get('liveCoding_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('liveCoding_view_type') === 'widgets') {
            $this->viewState->set("scope.liveCoding.visible", 1);
        }else{
            $this->viewState->remove("scope.liveCoding.visible");
        }
        
        // Récupération des données
        $liveCodings_data = $this->paginate($params);
        $liveCodings_stats = $this->getliveCodingStats();
        $liveCodings_total = $this->count();
        $liveCodings_filters = $this->getFieldsFilterable();
        $liveCoding_instance = $this->createInstance();
        $liveCoding_viewTypes = $this->getViewTypes();
        $liveCoding_partialViewName = $this->getPartialViewName($liveCoding_viewType);
        $liveCoding_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.liveCoding.stats', $liveCodings_stats);
    
        $liveCodings_permissions = [

            'edit-liveCoding' => Auth::user()->can('edit-liveCoding'),
            'destroy-liveCoding' => Auth::user()->can('destroy-liveCoding'),
            'show-liveCoding' => Auth::user()->can('show-liveCoding'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $liveCodings_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($liveCodings_data as $item) {
                $liveCodings_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        $scopeVariables = $this->viewState->getScopeVariablesTitles('liveCoding');

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'liveCoding_viewTypes',
            'liveCoding_viewType',
            'liveCodings_data',
            'liveCodings_stats',
            'liveCodings_total',
            'liveCodings_filters',
            'liveCoding_instance',
            'liveCoding_title',
            'contextKey',
            'liveCodings_permissions',
            'liveCodings_permissionsByItem',
            'scopeVariables'
        );
    
        return [
            'liveCodings_data' => $liveCodings_data,
            'liveCodings_stats' => $liveCodings_stats,
            'liveCodings_total' => $liveCodings_total,
            'liveCodings_filters' => $liveCodings_filters,
            'liveCoding_instance' => $liveCoding_instance,
            'liveCoding_viewType' => $liveCoding_viewType,
            'liveCoding_viewTypes' => $liveCoding_viewTypes,
            'liveCoding_partialViewName' => $liveCoding_partialViewName,
            'contextKey' => $contextKey,
            'liveCoding_compact_value' => $compact_value,
            'liveCodings_permissions' => $liveCodings_permissions,
            'liveCodings_permissionsByItem' => $liveCodings_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $liveCoding_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $liveCoding_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($liveCoding_ids as $id) {
            $liveCoding = $this->find($id);
            $this->authorize('update', $liveCoding);
    
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
            'commentaire'
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
    public function buildFieldMeta(LiveCoding $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgRealisationTache\App\Requests\LiveCodingRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'live_coding',
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
            case 'commentaire':
                return $this->computeFieldMeta($e, $field, $meta, 'text');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(LiveCoding $e, array $changes): LiveCoding
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
    public function formatDisplayValues(LiveCoding $e, array $fields): array
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



                case 'commentaire':
                    $html = view('Core::fields_by_type.text', [
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
