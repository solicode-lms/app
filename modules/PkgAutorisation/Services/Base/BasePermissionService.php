<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutorisation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutorisation\Models\Permission;
use Modules\Core\Services\BaseService;

/**
 * Classe PermissionService pour gérer la persistance de l'entité Permission.
 */
class BasePermissionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour permissions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'guard_name',
        'controller_id'
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
     * Constructeur de la classe PermissionService.
     */
    public function __construct()
    {
        parent::__construct(new Permission());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutorisation::permission.plural');
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
        $scopeVariables = $this->viewState->getScopeVariables('permission');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('controller_id', $scopeVariables)) {


                    $sysControllerService = new \Modules\Core\Services\SysControllerService();
                    $sysControllerIds = $this->getAvailableFilterValues('controller_id');
                    $sysControllers = $sysControllerService->getByIds($sysControllerIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysController.plural"), 
                        'controller_id', 
                        \Modules\Core\Models\SysController::class, 
                        'name',
                        $sysControllers
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de permission.
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
    public function getPermissionStats(): array
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
            'table' => 'PkgAutorisation::permission._table',
            default => 'PkgAutorisation::permission._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('permission_view_type', $default_view_type);
        $permission_viewType = $this->viewState->get('permission_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('permission_view_type') === 'widgets') {
            $this->viewState->set("scope.permission.visible", 1);
        }else{
            $this->viewState->remove("scope.permission.visible");
        }
        
        // Récupération des données
        $permissions_data = $this->paginate($params);
        $permissions_stats = $this->getpermissionStats();
        $permissions_total = $this->count();
        $permissions_filters = $this->getFieldsFilterable();
        $permission_instance = $this->createInstance();
        $permission_viewTypes = $this->getViewTypes();
        $permission_partialViewName = $this->getPartialViewName($permission_viewType);
        $permission_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.permission.stats', $permissions_stats);
    
        $permissions_permissions = [

            'edit-permission' => Auth::user()->can('edit-permission'),
            'destroy-permission' => Auth::user()->can('destroy-permission'),
            'show-permission' => Auth::user()->can('show-permission'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $permissions_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($permissions_data as $item) {
                $permissions_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'permission_viewTypes',
            'permission_viewType',
            'permissions_data',
            'permissions_stats',
            'permissions_total',
            'permissions_filters',
            'permission_instance',
            'permission_title',
            'contextKey',
            'permissions_permissions',
            'permissions_permissionsByItem'
        );
    
        return [
            'permissions_data' => $permissions_data,
            'permissions_stats' => $permissions_stats,
            'permissions_total' => $permissions_total,
            'permissions_filters' => $permissions_filters,
            'permission_instance' => $permission_instance,
            'permission_viewType' => $permission_viewType,
            'permission_viewTypes' => $permission_viewTypes,
            'permission_partialViewName' => $permission_partialViewName,
            'contextKey' => $contextKey,
            'permission_compact_value' => $compact_value,
            'permissions_permissions' => $permissions_permissions,
            'permissions_permissionsByItem' => $permissions_permissionsByItem
        ];
    }

}
