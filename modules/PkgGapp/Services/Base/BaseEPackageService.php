<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgGapp\Models\EPackage;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe EPackageService pour gérer la persistance de l'entité EPackage.
 */
class BaseEPackageService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour ePackages.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'reference',
        'name',
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
     * Constructeur de la classe EPackageService.
     */
    public function __construct()
    {
        parent::__construct(new EPackage());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::ePackage.plural');
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
            $ePackage = $this->find($data['id']);
            $ePackage->fill($data);
        } else {
            $ePackage = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($ePackage->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $ePackage->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($ePackage->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($ePackage->id, $data);
            }
        }

        return $ePackage;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('ePackage');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de ePackage.
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
    public function getEPackageStats(): array
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
            'table' => 'PkgGapp::ePackage._table',
            default => 'PkgGapp::ePackage._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('ePackage_view_type', $default_view_type);
        $ePackage_viewType = $this->viewState->get('ePackage_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('ePackage_view_type') === 'widgets') {
            $this->viewState->set("scope.ePackage.visible", 1);
        }else{
            $this->viewState->remove("scope.ePackage.visible");
        }
        
        // Récupération des données
        $ePackages_data = $this->paginate($params);
        $ePackages_stats = $this->getePackageStats();
        $ePackages_total = $this->count();
        $ePackages_filters = $this->getFieldsFilterable();
        $ePackage_instance = $this->createInstance();
        $ePackage_viewTypes = $this->getViewTypes();
        $ePackage_partialViewName = $this->getPartialViewName($ePackage_viewType);
        $ePackage_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.ePackage.stats', $ePackages_stats);
    
        $ePackages_permissions = [

            'edit-ePackage' => Auth::user()->can('edit-ePackage'),
            'destroy-ePackage' => Auth::user()->can('destroy-ePackage'),
            'show-ePackage' => Auth::user()->can('show-ePackage'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $ePackages_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($ePackages_data as $item) {
                $ePackages_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'ePackage_viewTypes',
            'ePackage_viewType',
            'ePackages_data',
            'ePackages_stats',
            'ePackages_total',
            'ePackages_filters',
            'ePackage_instance',
            'ePackage_title',
            'contextKey',
            'ePackages_permissions',
            'ePackages_permissionsByItem'
        );
    
        return [
            'ePackages_data' => $ePackages_data,
            'ePackages_stats' => $ePackages_stats,
            'ePackages_total' => $ePackages_total,
            'ePackages_filters' => $ePackages_filters,
            'ePackage_instance' => $ePackage_instance,
            'ePackage_viewType' => $ePackage_viewType,
            'ePackage_viewTypes' => $ePackage_viewTypes,
            'ePackage_partialViewName' => $ePackage_partialViewName,
            'contextKey' => $contextKey,
            'ePackage_compact_value' => $compact_value,
            'ePackages_permissions' => $ePackages_permissions,
            'ePackages_permissionsByItem' => $ePackages_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $ePackage_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $ePackage_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($ePackage_ids as $id) {
            $ePackage = $this->find($id);
            $this->authorize('update', $ePackage);
    
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
            'name'
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
    public function buildFieldMeta(EPackage $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgGapp\App\Requests\EPackageRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'e_package',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getInlineFieldsEditable()),
            'etag'           => $this->etag($e),
            'schema_version' => 'v1',
            'html_attrs'     => $htmlAttrs,
            'validation'     => $validationRules
        ];

       switch ($field) {
            case 'name':
                return $this->computeFieldMeta($e, $field, $meta, 'string');
            default:
                abort(404, "Champ $field non pris en charge pour l’édition inline.");
        }
    }

    /**
     * Applique un PATCH inline (validation + sauvegarde)
     */
    public function applyInlinePatch(EPackage $e, array $changes): EPackage
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
    public function formatDisplayValues(EPackage $e, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            switch ($field) {
                case 'name':
                    $html = view('Core::fields_by_type.string', [
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
