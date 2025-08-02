<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgGapp\Models\EDataField;
use Modules\Core\Services\BaseService;

/**
 * Classe EDataFieldService pour gérer la persistance de l'entité EDataField.
 */
class BaseEDataFieldService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eDataFields.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'e_model_id',
        'data_type',
        'default_value',
        'column_name',
        'e_relationship_id',
        'field_order',
        'db_primaryKey',
        'db_nullable',
        'db_unique',
        'calculable',
        'calculable_sql',
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
     * Constructeur de la classe EDataFieldService.
     */
    public function __construct()
    {
        parent::__construct(new EDataField());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::eDataField.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('eDataField');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('e_model_id', $scopeVariables)) {


                    $eModelService = new \Modules\PkgGapp\Services\EModelService();
                    $eModelIds = $this->getAvailableFilterValues('e_model_id');
                    $eModels = $eModelService->getByIds($eModelIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgGapp::eModel.plural"), 
                        'e_model_id', 
                        \Modules\PkgGapp\Models\EModel::class, 
                        'name',
                        $eModels
                    );
                }
            
            
                if (!array_key_exists('data_type', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'data_type', 
                        'type'  => 'String', 
                        'label' => 'data_type'
                    ];
                }
            
            
                if (!array_key_exists('calculable', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'calculable', 
                        'type'  => 'Boolean', 
                        'label' => 'calculable'
                    ];
                }
            



    }


    /**
     * Crée une nouvelle instance de eDataField.
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
    public function getEDataFieldStats(): array
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
            'table' => 'PkgGapp::eDataField._table',
            default => 'PkgGapp::eDataField._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('eDataField_view_type', $default_view_type);
        $eDataField_viewType = $this->viewState->get('eDataField_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('eDataField_view_type') === 'widgets') {
            $this->viewState->set("scope.eDataField.visible", 1);
        }else{
            $this->viewState->remove("scope.eDataField.visible");
        }
        
        // Récupération des données
        $eDataFields_data = $this->paginate($params);
        $eDataFields_stats = $this->geteDataFieldStats();
        $eDataFields_total = $this->count();
        $eDataFields_filters = $this->getFieldsFilterable();
        $eDataField_instance = $this->createInstance();
        $eDataField_viewTypes = $this->getViewTypes();
        $eDataField_partialViewName = $this->getPartialViewName($eDataField_viewType);
        $eDataField_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.eDataField.stats', $eDataFields_stats);
    
        $eDataFields_permissions = [

            'edit-eDataField' => Auth::user()->can('edit-eDataField'),
            'destroy-eDataField' => Auth::user()->can('destroy-eDataField'),
            'show-eDataField' => Auth::user()->can('show-eDataField'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $eDataFields_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($eDataFields_data as $item) {
                $eDataFields_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'eDataField_viewTypes',
            'eDataField_viewType',
            'eDataFields_data',
            'eDataFields_stats',
            'eDataFields_total',
            'eDataFields_filters',
            'eDataField_instance',
            'eDataField_title',
            'contextKey',
            'eDataFields_permissions',
            'eDataFields_permissionsByItem'
        );
    
        return [
            'eDataFields_data' => $eDataFields_data,
            'eDataFields_stats' => $eDataFields_stats,
            'eDataFields_total' => $eDataFields_total,
            'eDataFields_filters' => $eDataFields_filters,
            'eDataField_instance' => $eDataField_instance,
            'eDataField_viewType' => $eDataField_viewType,
            'eDataField_viewTypes' => $eDataField_viewTypes,
            'eDataField_partialViewName' => $eDataField_partialViewName,
            'contextKey' => $contextKey,
            'eDataField_compact_value' => $compact_value,
            'eDataFields_permissions' => $eDataFields_permissions,
            'eDataFields_permissionsByItem' => $eDataFields_permissionsByItem
        ];
    }

}
