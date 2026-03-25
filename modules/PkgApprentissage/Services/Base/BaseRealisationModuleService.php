<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationModule;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe RealisationModuleService pour gérer la persistance de l'entité RealisationModule.
 */
class BaseRealisationModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationModules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'module_id',
        'apprenant_id',
        'progression_cache',
        'etat_realisation_module_id',
        'note_cache',
        'bareme_cache',
        'dernier_update',
        'commentaire_formateur',
        'date_debut',
        'date_fin',
        'progression_ideal_cache',
        'taux_rythme_cache'
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
     * Constructeur de la classe RealisationModuleService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationModule());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationModule.plural');
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
            $realisationModule = $this->find($data['id']);
            $realisationModule->fill($data);
        } else {
            $realisationModule = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationModule->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationModule->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationModule->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationModule->id, $data);
            }
        }

        return $realisationModule;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationModule');
        $this->fieldsFilterable = [];
        
            
                $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                $filiereIds = $this->getAvailableFilterValues('Module.Filiere_id');
                $filieres = $filiereService->getByIds($filiereIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgFormation::filiere.plural"),
                    'Module.Filiere_id', 
                    \Modules\PkgFormation\Models\Filiere::class,
                    "id", 
                    "id",
                    $filieres,
                    "[name='module_id'],[name='Apprenant.groupes.id']",
                    route('modules.getData') . ',' . route('groupes.getData'),
                    "filiere_id,filiere_id"
                    
                );
            
            
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
            
            
                $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                $groupeIds = $this->getAvailableFilterValues('Apprenant.groupes.id');
                $groupes = $groupeService->getByIds($groupeIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgApprenants::groupe.plural"),
                    'Apprenant.groupes.id', 
                    \Modules\PkgApprenants\Models\Groupe::class,
                    "id", 
                    "id",
                    $groupes,
                    "[name='apprenant_id']",
                    route('apprenants.getData'),
                    "groupes.id"
                    
                );
            
            
                if (!array_key_exists('apprenant_id', $scopeVariables)) {


                    $apprenantService = new \Modules\PkgApprenants\Services\ApprenantService();
                    $apprenantIds = $this->getAvailableFilterValues('apprenant_id');
                    $apprenants = $apprenantService->getByIds($apprenantIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::apprenant.plural"), 
                        'apprenant_id', 
                        \Modules\PkgApprenants\Models\Apprenant::class, 
                        'nom',
                        $apprenants
                    );
                }
            
            
                if (!array_key_exists('etat_realisation_module_id', $scopeVariables)) {


                    $etatRealisationModuleService = new \Modules\PkgApprentissage\Services\EtatRealisationModuleService();
                    $etatRealisationModuleIds = $this->getAvailableFilterValues('etat_realisation_module_id');
                    $etatRealisationModules = $etatRealisationModuleService->getByIds($etatRealisationModuleIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::etatRealisationModule.plural"), 
                        'etat_realisation_module_id', 
                        \Modules\PkgApprentissage\Models\EtatRealisationModule::class, 
                        'code',
                        $etatRealisationModules
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationModule.
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
    public function getRealisationModuleStats(): array
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
            'table' => 'PkgApprentissage::realisationModule._table',
            default => 'PkgApprentissage::realisationModule._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationModule_view_type', $default_view_type);
        $realisationModule_viewType = $this->viewState->get('realisationModule_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationModule_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationModule.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationModule.visible");
        }
        
        // Récupération des données
        $realisationModules_data = $this->paginate($params);
        $realisationModules_stats = $this->getrealisationModuleStats();
        $realisationModules_total = $this->count();
        $realisationModules_filters = $this->getFieldsFilterable();
        $realisationModule_instance = $this->createInstance();
        $realisationModule_viewTypes = $this->getViewTypes();
        $realisationModule_partialViewName = $this->getPartialViewName($realisationModule_viewType);
        $realisationModule_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationModule.stats', $realisationModules_stats);
    
        $realisationModules_permissions = [

            'edit-realisationModule' => Auth::user()->can('edit-realisationModule'),
            'destroy-realisationModule' => Auth::user()->can('destroy-realisationModule'),
            'show-realisationModule' => Auth::user()->can('show-realisationModule'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationModules_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationModules_data as $item) {
                $realisationModules_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationModule_viewTypes',
            'realisationModule_viewType',
            'realisationModules_data',
            'realisationModules_stats',
            'realisationModules_total',
            'realisationModules_filters',
            'realisationModule_instance',
            'realisationModule_title',
            'contextKey',
            'realisationModules_permissions',
            'realisationModules_permissionsByItem'
        );
    
        return [
            'realisationModules_data' => $realisationModules_data,
            'realisationModules_stats' => $realisationModules_stats,
            'realisationModules_total' => $realisationModules_total,
            'realisationModules_filters' => $realisationModules_filters,
            'realisationModule_instance' => $realisationModule_instance,
            'realisationModule_viewType' => $realisationModule_viewType,
            'realisationModule_viewTypes' => $realisationModule_viewTypes,
            'realisationModule_partialViewName' => $realisationModule_partialViewName,
            'contextKey' => $contextKey,
            'realisationModule_compact_value' => $compact_value,
            'realisationModules_permissions' => $realisationModules_permissions,
            'realisationModules_permissionsByItem' => $realisationModules_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationModule_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationModule_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationModule_ids as $id) {
            $realisationModule = $this->find($id);
            $this->authorize('update', $realisationModule);
    
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
            'progression_cache',
            'note_cache'
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
    public function buildFieldMeta(RealisationModule $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgApprentissage\App\Requests\RealisationModuleRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'realisation_module',
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
            case 'progression_cache':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            case 'note_cache':
                return $this->computeFieldMeta($e, $field, $meta, 'number');

            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(RealisationModule $e, array $changes): RealisationModule
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
    public function formatDisplayValues(RealisationModule $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'module_id':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationModule.custom.fields.module', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'progression_cache':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationModule.custom.fields.progression_cache', [
                        'entity' => $e
                    ])->render();

                    $out[$field] = ['html' => $html];
                    break;

                case 'note_cache':
                    // Vue custom définie pour ce champ
                    $html = view('PkgApprentissage::realisationModule.custom.fields.note_cache', [
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
