<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgAutorisation\Models\Role;
use Modules\Core\Services\BaseService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\App\Helpers\ValidationRuleConverter;

/**
 * Classe RoleService pour gérer la persistance de l'entité Role.
 */
class BaseRoleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour roles.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'guard_name'
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
     * Constructeur de la classe RoleService.
     */
    public function __construct()
    {
        parent::__construct(new Role());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutorisation::role.plural');
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
            $role = $this->find($data['id']);
            $role->fill($data);
        } else {
            $role = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($role->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $role->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($role->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($role->id, $data);
            }
        }

        return $role;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('role');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de role.
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
    public function getRoleStats(): array
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
            'table' => 'PkgAutorisation::role._table',
            default => 'PkgAutorisation::role._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('role_view_type', $default_view_type);
        $role_viewType = $this->viewState->get('role_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('role_view_type') === 'widgets') {
            $this->viewState->set("scope.role.visible", 1);
        }else{
            $this->viewState->remove("scope.role.visible");
        }
        
        // Récupération des données
        $roles_data = $this->paginate($params);
        $roles_stats = $this->getroleStats();
        $roles_total = $this->count();
        $roles_filters = $this->getFieldsFilterable();
        $role_instance = $this->createInstance();
        $role_viewTypes = $this->getViewTypes();
        $role_partialViewName = $this->getPartialViewName($role_viewType);
        $role_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.role.stats', $roles_stats);
    
        $roles_permissions = [

            'edit-role' => Auth::user()->can('edit-role'),
            'destroy-role' => Auth::user()->can('destroy-role'),
            'show-role' => Auth::user()->can('show-role'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $roles_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($roles_data as $item) {
                $roles_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'role_viewTypes',
            'role_viewType',
            'roles_data',
            'roles_stats',
            'roles_total',
            'roles_filters',
            'role_instance',
            'role_title',
            'contextKey',
            'roles_permissions',
            'roles_permissionsByItem'
        );
    
        return [
            'roles_data' => $roles_data,
            'roles_stats' => $roles_stats,
            'roles_total' => $roles_total,
            'roles_filters' => $roles_filters,
            'role_instance' => $role_instance,
            'role_viewType' => $role_viewType,
            'role_viewTypes' => $role_viewTypes,
            'role_partialViewName' => $role_partialViewName,
            'contextKey' => $contextKey,
            'role_compact_value' => $compact_value,
            'roles_permissions' => $roles_permissions,
            'roles_permissionsByItem' => $roles_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $role_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $role_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($role_ids as $id) {
            $role = $this->find($id);
            $this->authorize('update', $role);
    
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
            'name'
        ];
    }


    /**
     * Construit les métadonnées d’un champ (type, options, validation…)
     */
    public function buildFieldMeta(Role $e, string $field): array
    {


        // 🔹 Récupérer toutes les règles définies dans le FormRequest
        $rules = (new \Modules\PkgAutorisation\App\Requests\RoleRequest())->rules();
        $validationRules = $rules[$field] ?? [];
        if (is_string($validationRules)) {
            $validationRules = explode('|', $validationRules);
        }

        $htmlAttrs = ValidationRuleConverter::toHtmlAttributes($validationRules, $e->toArray());

        $meta = [
            'entity'         => 'role',
            'id'             => $e->id,
            'field'          => $field,
            'writable'       => in_array($field, $this->getFieldsEditable()),
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
    public function applyInlinePatch(Role $e, array $changes): Role
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
        
        $e->fill($filtered);
        Validator::make($e->toArray(), $rules)->validate();
        $e = $this->updateOnlyExistanteAttribute($e->id, $filtered);

        return $e;
    }

    /**
     * Formatte les valeurs pour l’affichage inline
     */
    public function formatDisplayValues(Role $e, array $fields): array
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
