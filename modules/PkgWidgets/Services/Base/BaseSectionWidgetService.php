<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

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


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sectionWidget');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
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
        $sectionWidgets_filters = $this->getFieldsFilterable();
        $sectionWidget_instance = $this->createInstance();
        $sectionWidget_viewTypes = $this->getViewTypes();
        $sectionWidget_partialViewName = $this->getPartialViewName($sectionWidget_viewType);
        $sectionWidget_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sectionWidget.stats', $sectionWidgets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sectionWidget_viewTypes',
            'sectionWidget_viewType',
            'sectionWidgets_data',
            'sectionWidgets_stats',
            'sectionWidgets_filters',
            'sectionWidget_instance',
            'sectionWidget_title',
            'contextKey'
        );
    
        return [
            'sectionWidgets_data' => $sectionWidgets_data,
            'sectionWidgets_stats' => $sectionWidgets_stats,
            'sectionWidgets_filters' => $sectionWidgets_filters,
            'sectionWidget_instance' => $sectionWidget_instance,
            'sectionWidget_viewType' => $sectionWidget_viewType,
            'sectionWidget_viewTypes' => $sectionWidget_viewTypes,
            'sectionWidget_partialViewName' => $sectionWidget_partialViewName,
            'contextKey' => $contextKey,
            'sectionWidget_compact_value' => $compact_value
        ];
    }

}
