<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgFormation\Models\Module;
use Modules\Core\Services\BaseService;

/**
 * Classe ModuleService pour gérer la persistance de l'entité Module.
 */
class BaseModuleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour modules.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
        'masse_horaire',
        'filiere_id'
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
     * Constructeur de la classe ModuleService.
     */
    public function __construct()
    {
        parent::__construct(new Module());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::module.plural');
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
            $module = $this->find($data['id']);
            $module->fill($data);
        } else {
            $module = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($module->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $module->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($module->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($module->id, $data);
            }
        }

        return $module;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('module');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('filiere_id', $scopeVariables)) {


                    $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                    $filiereIds = $this->getAvailableFilterValues('filiere_id');
                    $filieres = $filiereService->getByIds($filiereIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::filiere.plural"), 
                        'filiere_id', 
                        \Modules\PkgFormation\Models\Filiere::class, 
                        'code',
                        $filieres
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de module.
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
    public function getModuleStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Filiere::class,
                'modules',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

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
            'table' => 'PkgFormation::module._table',
            default => 'PkgFormation::module._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('module_view_type', $default_view_type);
        $module_viewType = $this->viewState->get('module_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('module_view_type') === 'widgets') {
            $this->viewState->set("scope.module.visible", 1);
        }else{
            $this->viewState->remove("scope.module.visible");
        }
        
        // Récupération des données
        $modules_data = $this->paginate($params);
        $modules_stats = $this->getmoduleStats();
        $modules_total = $this->count();
        $modules_filters = $this->getFieldsFilterable();
        $module_instance = $this->createInstance();
        $module_viewTypes = $this->getViewTypes();
        $module_partialViewName = $this->getPartialViewName($module_viewType);
        $module_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.module.stats', $modules_stats);
    
        $modules_permissions = [

            'edit-module' => Auth::user()->can('edit-module'),
            'destroy-module' => Auth::user()->can('destroy-module'),
            'show-module' => Auth::user()->can('show-module'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $modules_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($modules_data as $item) {
                $modules_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'module_viewTypes',
            'module_viewType',
            'modules_data',
            'modules_stats',
            'modules_total',
            'modules_filters',
            'module_instance',
            'module_title',
            'contextKey',
            'modules_permissions',
            'modules_permissionsByItem'
        );
    
        return [
            'modules_data' => $modules_data,
            'modules_stats' => $modules_stats,
            'modules_total' => $modules_total,
            'modules_filters' => $modules_filters,
            'module_instance' => $module_instance,
            'module_viewType' => $module_viewType,
            'module_viewTypes' => $module_viewTypes,
            'module_partialViewName' => $module_partialViewName,
            'contextKey' => $contextKey,
            'module_compact_value' => $compact_value,
            'modules_permissions' => $modules_permissions,
            'modules_permissionsByItem' => $modules_permissionsByItem
        ];
    }

}
