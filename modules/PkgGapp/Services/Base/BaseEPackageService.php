<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgGapp\Models\EPackage;
use Modules\Core\Services\BaseService;

/**
 * Classe EPackageService pour gérer la persistance de l'entité EPackage.
 */
class BaseEPackageService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour ePackages.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
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
     * Constructeur de la classe EPackageService.
     */
    public function __construct()
    {
        parent::__construct(new EPackage());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGapp::ePackage.plural');
    }


    public function dataCalcul($ePackage)
    {
        // En Cas d'édit
        if(isset($ePackage->id)){
          
        }
      
        return $ePackage;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('ePackage');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de ePackage.
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
    public function getEPackageStats(): array
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
            'table' => 'PkgGapp::ePackage._table',
            default => 'PkgGapp::ePackage._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('ePackage_view_type', $default_view_type);
        $ePackage_viewType = $this->viewState->get('ePackage_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('ePackage_view_type') === 'widgets') {
            $this->viewState->set("scope.ePackage.visible", 1);
        }else{
            $this->viewState->remove("scope.ePackage.visible");
        }
        
        // Récupération des données
        $ePackages_data = $this->paginate($params);
        $ePackages_stats = $this->getePackageStats();
        $ePackages_total = $this->count();
        $ePackages_filters = $this->getFieldsFilterable();
        $ePackage_instance = $this->createInstance();
        $ePackage_viewTypes = $this->getViewTypes();
        $ePackage_partialViewName = $this->getPartialViewName($ePackage_viewType);
        $ePackage_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.ePackage.stats', $ePackages_stats);
    
        $ePackages_permissions = [

            'edit-ePackage' => Auth::user()->can('edit-ePackage'),
            'destroy-ePackage' => Auth::user()->can('destroy-ePackage'),
            'show-ePackage' => Auth::user()->can('show-ePackage'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $ePackages_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($ePackages_data as $item) {
                $ePackages_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'ePackage_viewTypes',
            'ePackage_viewType',
            'ePackages_data',
            'ePackages_stats',
            'ePackages_total',
            'ePackages_filters',
            'ePackage_instance',
            'ePackage_title',
            'contextKey',
            'ePackages_permissions',
            'ePackages_permissionsByItem'
        );
    
        return [
            'ePackages_data' => $ePackages_data,
            'ePackages_stats' => $ePackages_stats,
            'ePackages_total' => $ePackages_total,
            'ePackages_filters' => $ePackages_filters,
            'ePackage_instance' => $ePackage_instance,
            'ePackage_viewType' => $ePackage_viewType,
            'ePackage_viewTypes' => $ePackage_viewTypes,
            'ePackage_partialViewName' => $ePackage_partialViewName,
            'contextKey' => $contextKey,
            'ePackage_compact_value' => $compact_value,
            'ePackages_permissions' => $ePackages_permissions,
            'ePackages_permissionsByItem' => $ePackages_permissionsByItem
        ];
    }

}
