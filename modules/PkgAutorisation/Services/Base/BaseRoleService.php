<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutorisation\Models\Role;
use Modules\Core\Services\BaseService;

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
            $realisationTache = $this->find($data['id']);
            $realisationTache->fill($data);
        } else {
            $realisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationTache->id, $data);
            }
        }

        return $realisationTache;
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

}
