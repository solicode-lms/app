<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgWidgets\Models\SectionWidget;
use Modules\Core\Services\BaseService;

/**
 * Classe SectionWidgetService pour gérer la persistance de l'entité SectionWidget.
 */
class BaseSectionWidgetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sectionWidgets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'icone',
        'titre',
        'sous_titre',
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
     * Constructeur de la classe SectionWidgetService.
     */
    public function __construct()
    {
        parent::__construct(new SectionWidget());
        $this->fieldsFilterable = [];
        $this->title = __('PkgWidgets::sectionWidget.plural');
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
            $sectionWidget = $this->find($data['id']);
            $sectionWidget->fill($data);
        } else {
            $sectionWidget = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($sectionWidget->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $sectionWidget->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($sectionWidget->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($sectionWidget->id, $data);
            }
        }

        return $sectionWidget;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sectionWidget');
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
     * Crée une nouvelle instance de sectionWidget.
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
    public function getSectionWidgetStats(): array
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
            'table' => 'PkgWidgets::sectionWidget._table',
            default => 'PkgWidgets::sectionWidget._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sectionWidget_view_type', $default_view_type);
        $sectionWidget_viewType = $this->viewState->get('sectionWidget_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sectionWidget_view_type') === 'widgets') {
            $this->viewState->set("scope.sectionWidget.visible", 1);
        }else{
            $this->viewState->remove("scope.sectionWidget.visible");
        }
        
        // Récupération des données
        $sectionWidgets_data = $this->paginate($params);
        $sectionWidgets_stats = $this->getsectionWidgetStats();
        $sectionWidgets_total = $this->count();
        $sectionWidgets_filters = $this->getFieldsFilterable();
        $sectionWidget_instance = $this->createInstance();
        $sectionWidget_viewTypes = $this->getViewTypes();
        $sectionWidget_partialViewName = $this->getPartialViewName($sectionWidget_viewType);
        $sectionWidget_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sectionWidget.stats', $sectionWidgets_stats);
    
        $sectionWidgets_permissions = [

            'edit-sectionWidget' => Auth::user()->can('edit-sectionWidget'),
            'destroy-sectionWidget' => Auth::user()->can('destroy-sectionWidget'),
            'show-sectionWidget' => Auth::user()->can('show-sectionWidget'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sectionWidgets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sectionWidgets_data as $item) {
                $sectionWidgets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sectionWidget_viewTypes',
            'sectionWidget_viewType',
            'sectionWidgets_data',
            'sectionWidgets_stats',
            'sectionWidgets_total',
            'sectionWidgets_filters',
            'sectionWidget_instance',
            'sectionWidget_title',
            'contextKey',
            'sectionWidgets_permissions',
            'sectionWidgets_permissionsByItem'
        );
    
        return [
            'sectionWidgets_data' => $sectionWidgets_data,
            'sectionWidgets_stats' => $sectionWidgets_stats,
            'sectionWidgets_total' => $sectionWidgets_total,
            'sectionWidgets_filters' => $sectionWidgets_filters,
            'sectionWidget_instance' => $sectionWidget_instance,
            'sectionWidget_viewType' => $sectionWidget_viewType,
            'sectionWidget_viewTypes' => $sectionWidget_viewTypes,
            'sectionWidget_partialViewName' => $sectionWidget_partialViewName,
            'contextKey' => $contextKey,
            'sectionWidget_compact_value' => $compact_value,
            'sectionWidgets_permissions' => $sectionWidgets_permissions,
            'sectionWidgets_permissionsByItem' => $sectionWidgets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $sectionWidget_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $sectionWidget_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($sectionWidget_ids as $id) {
            $sectionWidget = $this->find($id);
            $this->authorize('update', $sectionWidget);
    
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
