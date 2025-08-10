<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\Core\Models\FeatureDomain;
use Modules\Core\Services\BaseService;

/**
 * Classe FeatureDomainService pour gérer la persistance de l'entité FeatureDomain.
 */
class BaseFeatureDomainService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour featureDomains.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'slug',
        'description',
        'sys_module_id'
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
     * Constructeur de la classe FeatureDomainService.
     */
    public function __construct()
    {
        parent::__construct(new FeatureDomain());
        $this->fieldsFilterable = [];
        $this->title = __('Core::featureDomain.plural');
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
            $featureDomain = $this->find($data['id']);
            $featureDomain->fill($data);
        } else {
            $featureDomain = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($featureDomain->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $featureDomain->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($featureDomain->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($featureDomain->id, $data);
            }
        }

        return $featureDomain;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('featureDomain');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('sys_module_id', $scopeVariables)) {


                    $sysModuleService = new \Modules\Core\Services\SysModuleService();
                    $sysModuleIds = $this->getAvailableFilterValues('sys_module_id');
                    $sysModules = $sysModuleService->getByIds($sysModuleIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysModule.plural"), 
                        'sys_module_id', 
                        \Modules\Core\Models\SysModule::class, 
                        'name',
                        $sysModules
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de featureDomain.
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
    public function getFeatureDomainStats(): array
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
            'table' => 'Core::featureDomain._table',
            default => 'Core::featureDomain._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('featureDomain_view_type', $default_view_type);
        $featureDomain_viewType = $this->viewState->get('featureDomain_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('featureDomain_view_type') === 'widgets') {
            $this->viewState->set("scope.featureDomain.visible", 1);
        }else{
            $this->viewState->remove("scope.featureDomain.visible");
        }
        
        // Récupération des données
        $featureDomains_data = $this->paginate($params);
        $featureDomains_stats = $this->getfeatureDomainStats();
        $featureDomains_total = $this->count();
        $featureDomains_filters = $this->getFieldsFilterable();
        $featureDomain_instance = $this->createInstance();
        $featureDomain_viewTypes = $this->getViewTypes();
        $featureDomain_partialViewName = $this->getPartialViewName($featureDomain_viewType);
        $featureDomain_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.featureDomain.stats', $featureDomains_stats);
    
        $featureDomains_permissions = [

            'edit-featureDomain' => Auth::user()->can('edit-featureDomain'),
            'destroy-featureDomain' => Auth::user()->can('destroy-featureDomain'),
            'show-featureDomain' => Auth::user()->can('show-featureDomain'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $featureDomains_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($featureDomains_data as $item) {
                $featureDomains_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'featureDomain_viewTypes',
            'featureDomain_viewType',
            'featureDomains_data',
            'featureDomains_stats',
            'featureDomains_total',
            'featureDomains_filters',
            'featureDomain_instance',
            'featureDomain_title',
            'contextKey',
            'featureDomains_permissions',
            'featureDomains_permissionsByItem'
        );
    
        return [
            'featureDomains_data' => $featureDomains_data,
            'featureDomains_stats' => $featureDomains_stats,
            'featureDomains_total' => $featureDomains_total,
            'featureDomains_filters' => $featureDomains_filters,
            'featureDomain_instance' => $featureDomain_instance,
            'featureDomain_viewType' => $featureDomain_viewType,
            'featureDomain_viewTypes' => $featureDomain_viewTypes,
            'featureDomain_partialViewName' => $featureDomain_partialViewName,
            'contextKey' => $contextKey,
            'featureDomain_compact_value' => $compact_value,
            'featureDomains_permissions' => $featureDomains_permissions,
            'featureDomains_permissionsByItem' => $featureDomains_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $featureDomain_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $featureDomain_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($featureDomain_ids as $id) {
            $featureDomain = $this->find($id);
            $this->authorize('update', $featureDomain);
    
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
