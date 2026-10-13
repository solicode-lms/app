<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgTableauBord\Models\Remediation;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe RemediationService pour gérer la persistance de l'entité Remediation.
 */
class BaseRemediationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour remediations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'realisation_tache_id',
        'formateur_id',
        'type_remediation',
        'diagnostic',
        'est_resolu',
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
     * Constructeur de la classe RemediationService.
     */
    public function __construct()
    {
        parent::__construct(new Remediation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgTableauBord::remediation.plural');
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
            $remediation = $this->find($data['id']);
            $remediation->fill($data);
        } else {
            $remediation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($remediation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $remediation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($remediation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($remediation->id, $data);
            }
        }

        return $remediation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('remediation');
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
     * Crée une nouvelle instance de remediation.
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
    public function getRemediationStats(): array
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
            'table' => 'PkgTableauBord::remediation._table',
            default => 'PkgTableauBord::remediation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('remediation_view_type', $default_view_type);
        $remediation_viewType = $this->viewState->get('remediation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('remediation_view_type') === 'widgets') {
            $this->viewState->set("scope.remediation.visible", 1);
        }else{
            $this->viewState->remove("scope.remediation.visible");
        }
        
        // Récupération des données
        $remediations_data = $this->paginate($params);
        $remediations_stats = $this->getremediationStats();
        $remediations_total = $this->count();
        $remediations_filters = $this->getFieldsFilterable();
        $remediation_instance = $this->createInstance();
        $remediation_viewTypes = $this->getViewTypes();
        $remediation_partialViewName = $this->getPartialViewName($remediation_viewType);
        $remediation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.remediation.stats', $remediations_stats);
    
        $remediations_permissions = [

            'edit-remediation' => Auth::user()->can('edit-remediation'),
            'destroy-remediation' => Auth::user()->can('destroy-remediation'),
            'show-remediation' => Auth::user()->can('show-remediation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $remediations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($remediations_data as $item) {
                $remediations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        $scopeVariables = $this->viewState->getScopeVariablesTitles('remediation');

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'remediation_viewTypes',
            'remediation_viewType',
            'remediations_data',
            'remediations_stats',
            'remediations_total',
            'remediations_filters',
            'remediation_instance',
            'remediation_title',
            'contextKey',
            'remediations_permissions',
            'remediations_permissionsByItem',
            'scopeVariables'
        );
    
        return [
            'remediations_data' => $remediations_data,
            'remediations_stats' => $remediations_stats,
            'remediations_total' => $remediations_total,
            'remediations_filters' => $remediations_filters,
            'remediation_instance' => $remediation_instance,
            'remediation_viewType' => $remediation_viewType,
            'remediation_viewTypes' => $remediation_viewTypes,
            'remediation_partialViewName' => $remediation_partialViewName,
            'contextKey' => $contextKey,
            'remediation_compact_value' => $compact_value,
            'remediations_permissions' => $remediations_permissions,
            'remediations_permissionsByItem' => $remediations_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $remediation_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $remediation_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($remediation_ids as $id) {
            $remediation = $this->find($id);
            $this->authorize('update', $remediation);
    
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
    public function buildFieldMeta(Remediation $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgTableauBord\App\Requests\RemediationRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'remediation',
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
    public function applyInlinePatch(Remediation $e, array $changes): Remediation
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
    public function formatDisplayValues(Remediation $e, array $fields): array
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
