<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\Models\SysModule;
use Modules\Core\Services\BaseService;

/**
 * Classe SysModuleService pour gérer la persistance de l'entité SysModule.
 */
class BaseSysModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sysModules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'name',
        'slug',
        'description',
        'is_active',
        'version',
        'sys_color_id'
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
     * Constructeur de la classe SysModuleService.
     */
    public function __construct()
    {
        parent::__construct(new SysModule());
        $this->fieldsFilterable = [];
        $this->title = __('Core::sysModule.plural');
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
            $sysModule = $this->find($data['id']);
            $sysModule->fill($data);
        } else {
            $sysModule = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($sysModule->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $sysModule->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($sysModule->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($sysModule->id, $data);
            }
        }

        return $sysModule;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sysModule');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('sys_color_id', $scopeVariables)) {


                    $sysColorService = new \Modules\Core\Services\SysColorService();
                    $sysColorIds = $this->getAvailableFilterValues('sys_color_id');
                    $sysColors = $sysColorService->getByIds($sysColorIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysColor.plural"), 
                        'sys_color_id', 
                        \Modules\Core\Models\SysColor::class, 
                        'name',
                        $sysColors
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de sysModule.
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
    public function getSysModuleStats(): array
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
            'table' => 'Core::sysModule._table',
            default => 'Core::sysModule._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sysModule_view_type', $default_view_type);
        $sysModule_viewType = $this->viewState->get('sysModule_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sysModule_view_type') === 'widgets') {
            $this->viewState->set("scope.sysModule.visible", 1);
        }else{
            $this->viewState->remove("scope.sysModule.visible");
        }
        
        // Récupération des données
        $sysModules_data = $this->paginate($params);
        $sysModules_stats = $this->getsysModuleStats();
        $sysModules_total = $this->count();
        $sysModules_filters = $this->getFieldsFilterable();
        $sysModule_instance = $this->createInstance();
        $sysModule_viewTypes = $this->getViewTypes();
        $sysModule_partialViewName = $this->getPartialViewName($sysModule_viewType);
        $sysModule_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sysModule.stats', $sysModules_stats);
    
        $sysModules_permissions = [

            'edit-sysModule' => Auth::user()->can('edit-sysModule'),
            'destroy-sysModule' => Auth::user()->can('destroy-sysModule'),
            'show-sysModule' => Auth::user()->can('show-sysModule'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sysModules_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sysModules_data as $item) {
                $sysModules_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sysModule_viewTypes',
            'sysModule_viewType',
            'sysModules_data',
            'sysModules_stats',
            'sysModules_total',
            'sysModules_filters',
            'sysModule_instance',
            'sysModule_title',
            'contextKey',
            'sysModules_permissions',
            'sysModules_permissionsByItem'
        );
    
        return [
            'sysModules_data' => $sysModules_data,
            'sysModules_stats' => $sysModules_stats,
            'sysModules_total' => $sysModules_total,
            'sysModules_filters' => $sysModules_filters,
            'sysModule_instance' => $sysModule_instance,
            'sysModule_viewType' => $sysModule_viewType,
            'sysModule_viewTypes' => $sysModule_viewTypes,
            'sysModule_partialViewName' => $sysModule_partialViewName,
            'contextKey' => $contextKey,
            'sysModule_compact_value' => $compact_value,
            'sysModules_permissions' => $sysModules_permissions,
            'sysModules_permissionsByItem' => $sysModules_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $sysModule_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $sysModule_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($sysModule_ids as $id) {
            $sysModule = $this->find($id);
            $this->authorize('update', $sysModule);
    
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

}
