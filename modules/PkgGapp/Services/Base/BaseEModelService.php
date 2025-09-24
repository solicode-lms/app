<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\Models\EModel;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EModelService pour gérer la persistance de l'entité EModel.
 */
class BaseEModelService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eModels.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'table_name',
        'icon',
        'is_pivot_table',
        'description',
        'e_package_id'
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
     * Constructeur de la classe EModelService.
     */
    public function __construct()
    {
        parent::__construct(new EModel());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::eModel.plural');
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
            $eModel = $this->find($data['id']);
            $eModel->fill($data);
        } else {
            $eModel = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($eModel->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $eModel->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($eModel->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($eModel->id, $data);
            }
        }

        return $eModel;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eModel');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('e_package_id', $scopeVariables)) {


                    $ePackageService = new \Modules\PkgGapp\Services\EPackageService();
                    $ePackageIds = $this->getAvailableFilterValues('e_package_id');
                    $ePackages = $ePackageService->getByIds($ePackageIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::ePackage.plural"), 
                        'e_package_id', 
                        \Modules\PkgGapp\Models\EPackage::class, 
                        'name',
                        $ePackages
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de eModel.
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
    public function getEModelStats(): array
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
            'table' => 'PkgGapp::eModel._table',
            default => 'PkgGapp::eModel._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('eModel_view_type', $default_view_type);
        $eModel_viewType = $this->viewState->get('eModel_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eModel_view_type') === 'widgets') {
            $this->viewState->set("scope.eModel.visible", 1);
        }else{
            $this->viewState->remove("scope.eModel.visible");
        }
        
        // Récupération des données
        $eModels_data = $this->paginate($params);
        $eModels_stats = $this->geteModelStats();
        $eModels_total = $this->count();
        $eModels_filters = $this->getFieldsFilterable();
        $eModel_instance = $this->createInstance();
        $eModel_viewTypes = $this->getViewTypes();
        $eModel_partialViewName = $this->getPartialViewName($eModel_viewType);
        $eModel_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eModel.stats', $eModels_stats);
    
        $eModels_permissions = [

            'edit-eModel' => Auth::user()->can('edit-eModel'),
            'destroy-eModel' => Auth::user()->can('destroy-eModel'),
            'show-eModel' => Auth::user()->can('show-eModel'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $eModels_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($eModels_data as $item) {
                $eModels_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eModel_viewTypes',
            'eModel_viewType',
            'eModels_data',
            'eModels_stats',
            'eModels_total',
            'eModels_filters',
            'eModel_instance',
            'eModel_title',
            'contextKey',
            'eModels_permissions',
            'eModels_permissionsByItem'
        );
    
        return [
            'eModels_data' => $eModels_data,
            'eModels_stats' => $eModels_stats,
            'eModels_total' => $eModels_total,
            'eModels_filters' => $eModels_filters,
            'eModel_instance' => $eModel_instance,
            'eModel_viewType' => $eModel_viewType,
            'eModel_viewTypes' => $eModel_viewTypes,
            'eModel_partialViewName' => $eModel_partialViewName,
            'contextKey' => $contextKey,
            'eModel_compact_value' => $compact_value,
            'eModels_permissions' => $eModels_permissions,
            'eModels_permissionsByItem' => $eModels_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $eModel_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $eModel_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($eModel_ids as $id) {
            $eModel = $this->find($id);
            $this->authorize('update', $eModel);
    
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
            'icone',
            'name',
            'e_package_id'
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
    public function buildFieldMeta(EModel $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgGapp\App\Requests\EModelRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'e_model',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'icone':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            case 'e_package_id':
                 $values = (new \Modules\PkgGapp\Services\EPackageService())
                    ->getAllForSelect($e->ePackage)
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
    public function applyInlinePatch(EModel $e, array $changes): EModel
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
    public function formatDisplayValues(EModel $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'icone':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => 'icone'
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'name':
                    $html = view('Core::fields_by_type.string', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => ''
                    ])->render();
                    $out[$field] = ['html' => $html];
                    break;
                case 'e_package_id':
                    $html = view('Core::fields_by_type.manytoone', [
                        'entity' => $e,
                        'column' => $field,
                        'nature' => '',
                        'relationName' => 'ePackage'
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
