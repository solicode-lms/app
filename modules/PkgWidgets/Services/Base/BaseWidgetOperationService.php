<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgWidgets\Models\WidgetOperation;
use Modules\Core\Services\BaseService;

/**
 * Classe WidgetOperationService pour gérer la persistance de l'entité WidgetOperation.
 */
class BaseWidgetOperationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour widgetOperations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'operation',
        'description'
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
     * Constructeur de la classe WidgetOperationService.
     */
    public function __construct()
    {
        parent::__construct(new WidgetOperation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgWidgets::widgetOperation.plural');
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
            $widgetOperation = $this->find($data['id']);
            $widgetOperation->fill($data);
        } else {
            $widgetOperation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($widgetOperation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $widgetOperation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($widgetOperation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($widgetOperation->id, $data);
            }
        }

        return $widgetOperation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('widgetOperation');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de widgetOperation.
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
    public function getWidgetOperationStats(): array
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
            'table' => 'PkgWidgets::widgetOperation._table',
            default => 'PkgWidgets::widgetOperation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('widgetOperation_view_type', $default_view_type);
        $widgetOperation_viewType = $this->viewState->get('widgetOperation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('widgetOperation_view_type') === 'widgets') {
            $this->viewState->set("scope.widgetOperation.visible", 1);
        }else{
            $this->viewState->remove("scope.widgetOperation.visible");
        }
        
        // Récupération des données
        $widgetOperations_data = $this->paginate($params);
        $widgetOperations_stats = $this->getwidgetOperationStats();
        $widgetOperations_total = $this->count();
        $widgetOperations_filters = $this->getFieldsFilterable();
        $widgetOperation_instance = $this->createInstance();
        $widgetOperation_viewTypes = $this->getViewTypes();
        $widgetOperation_partialViewName = $this->getPartialViewName($widgetOperation_viewType);
        $widgetOperation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.widgetOperation.stats', $widgetOperations_stats);
    
        $widgetOperations_permissions = [

            'edit-widgetOperation' => Auth::user()->can('edit-widgetOperation'),
            'destroy-widgetOperation' => Auth::user()->can('destroy-widgetOperation'),
            'show-widgetOperation' => Auth::user()->can('show-widgetOperation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $widgetOperations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($widgetOperations_data as $item) {
                $widgetOperations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'widgetOperation_viewTypes',
            'widgetOperation_viewType',
            'widgetOperations_data',
            'widgetOperations_stats',
            'widgetOperations_total',
            'widgetOperations_filters',
            'widgetOperation_instance',
            'widgetOperation_title',
            'contextKey',
            'widgetOperations_permissions',
            'widgetOperations_permissionsByItem'
        );
    
        return [
            'widgetOperations_data' => $widgetOperations_data,
            'widgetOperations_stats' => $widgetOperations_stats,
            'widgetOperations_total' => $widgetOperations_total,
            'widgetOperations_filters' => $widgetOperations_filters,
            'widgetOperation_instance' => $widgetOperation_instance,
            'widgetOperation_viewType' => $widgetOperation_viewType,
            'widgetOperation_viewTypes' => $widgetOperation_viewTypes,
            'widgetOperation_partialViewName' => $widgetOperation_partialViewName,
            'contextKey' => $contextKey,
            'widgetOperation_compact_value' => $compact_value,
            'widgetOperations_permissions' => $widgetOperations_permissions,
            'widgetOperations_permissionsByItem' => $widgetOperations_permissionsByItem
        ];
    }

}
