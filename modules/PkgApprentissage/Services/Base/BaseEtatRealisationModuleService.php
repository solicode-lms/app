<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\EtatRealisationModule;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatRealisationModuleService pour gérer la persistance de l'entité EtatRealisationModule.
 */
class BaseEtatRealisationModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationModules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'description',
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
     * Constructeur de la classe EtatRealisationModuleService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationModule());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::etatRealisationModule.plural');
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
            $etatRealisationModule = $this->find($data['id']);
            $etatRealisationModule->fill($data);
        } else {
            $etatRealisationModule = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatRealisationModule->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatRealisationModule->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatRealisationModule->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatRealisationModule->id, $data);
            }
        }

        return $etatRealisationModule;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationModule');
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
     * Crée une nouvelle instance de etatRealisationModule.
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
    public function getEtatRealisationModuleStats(): array
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
            'table' => 'PkgApprentissage::etatRealisationModule._table',
            default => 'PkgApprentissage::etatRealisationModule._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationModule_view_type', $default_view_type);
        $etatRealisationModule_viewType = $this->viewState->get('etatRealisationModule_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationModule_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationModule.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationModule.visible");
        }
        
        // Récupération des données
        $etatRealisationModules_data = $this->paginate($params);
        $etatRealisationModules_stats = $this->getetatRealisationModuleStats();
        $etatRealisationModules_total = $this->count();
        $etatRealisationModules_filters = $this->getFieldsFilterable();
        $etatRealisationModule_instance = $this->createInstance();
        $etatRealisationModule_viewTypes = $this->getViewTypes();
        $etatRealisationModule_partialViewName = $this->getPartialViewName($etatRealisationModule_viewType);
        $etatRealisationModule_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationModule.stats', $etatRealisationModules_stats);
    
        $etatRealisationModules_permissions = [

            'edit-etatRealisationModule' => Auth::user()->can('edit-etatRealisationModule'),
            'destroy-etatRealisationModule' => Auth::user()->can('destroy-etatRealisationModule'),
            'show-etatRealisationModule' => Auth::user()->can('show-etatRealisationModule'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationModules_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationModules_data as $item) {
                $etatRealisationModules_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationModule_viewTypes',
            'etatRealisationModule_viewType',
            'etatRealisationModules_data',
            'etatRealisationModules_stats',
            'etatRealisationModules_total',
            'etatRealisationModules_filters',
            'etatRealisationModule_instance',
            'etatRealisationModule_title',
            'contextKey',
            'etatRealisationModules_permissions',
            'etatRealisationModules_permissionsByItem'
        );
    
        return [
            'etatRealisationModules_data' => $etatRealisationModules_data,
            'etatRealisationModules_stats' => $etatRealisationModules_stats,
            'etatRealisationModules_total' => $etatRealisationModules_total,
            'etatRealisationModules_filters' => $etatRealisationModules_filters,
            'etatRealisationModule_instance' => $etatRealisationModule_instance,
            'etatRealisationModule_viewType' => $etatRealisationModule_viewType,
            'etatRealisationModule_viewTypes' => $etatRealisationModule_viewTypes,
            'etatRealisationModule_partialViewName' => $etatRealisationModule_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationModule_compact_value' => $compact_value,
            'etatRealisationModules_permissions' => $etatRealisationModules_permissions,
            'etatRealisationModules_permissionsByItem' => $etatRealisationModules_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatRealisationModule_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatRealisationModule_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatRealisationModule_ids as $id) {
            $etatRealisationModule = $this->find($id);
            $this->authorize('update', $etatRealisationModule);
    
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
