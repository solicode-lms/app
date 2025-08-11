<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationModule;
use Modules\Core\Services\BaseService;

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
        'date_fin'
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
                        'nom',
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

}
