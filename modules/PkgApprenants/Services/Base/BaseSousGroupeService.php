<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprenants\Models\SousGroupe;
use Modules\Core\Services\BaseService;

/**
 * Classe SousGroupeService pour gérer la persistance de l'entité SousGroupe.
 */
class BaseSousGroupeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sousGroupes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'groupe_id'
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
     * Constructeur de la classe SousGroupeService.
     */
    public function __construct()
    {
        parent::__construct(new SousGroupe());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::sousGroupe.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sousGroupe');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('groupe_id', $scopeVariables)) {
                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::groupe.plural"), 
                        'groupe_id', 
                        \Modules\PkgApprenants\Models\Groupe::class, 
                        'code'
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de sousGroupe.
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
    public function getSousGroupeStats(): array
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
            'table' => 'PkgApprenants::sousGroupe._table',
            default => 'PkgApprenants::sousGroupe._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sousGroupe_view_type', $default_view_type);
        $sousGroupe_viewType = $this->viewState->get('sousGroupe_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sousGroupe_view_type') === 'widgets') {
            $this->viewState->set("scope.sousGroupe.visible", 1);
        }else{
            $this->viewState->remove("scope.sousGroupe.visible");
        }
        
        // Récupération des données
        $sousGroupes_data = $this->paginate($params);
        $sousGroupes_stats = $this->getsousGroupeStats();
        $sousGroupes_filters = $this->getFieldsFilterable();
        $sousGroupe_instance = $this->createInstance();
        $sousGroupe_viewTypes = $this->getViewTypes();
        $sousGroupe_partialViewName = $this->getPartialViewName($sousGroupe_viewType);
        $sousGroupe_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sousGroupe.stats', $sousGroupes_stats);
    
        $sousGroupes_permissions = [

            'edit-sousGroupe' => Auth::user()->can('edit-sousGroupe'),
            'destroy-sousGroupe' => Auth::user()->can('destroy-sousGroupe'),
            'show-sousGroupe' => Auth::user()->can('show-sousGroupe'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sousGroupes_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sousGroupes_data as $item) {
                $sousGroupes_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sousGroupe_viewTypes',
            'sousGroupe_viewType',
            'sousGroupes_data',
            'sousGroupes_stats',
            'sousGroupes_filters',
            'sousGroupe_instance',
            'sousGroupe_title',
            'contextKey',
            'sousGroupes_permissions',
            'sousGroupes_permissionsByItem'
        );
    
        return [
            'sousGroupes_data' => $sousGroupes_data,
            'sousGroupes_stats' => $sousGroupes_stats,
            'sousGroupes_filters' => $sousGroupes_filters,
            'sousGroupe_instance' => $sousGroupe_instance,
            'sousGroupe_viewType' => $sousGroupe_viewType,
            'sousGroupe_viewTypes' => $sousGroupe_viewTypes,
            'sousGroupe_partialViewName' => $sousGroupe_partialViewName,
            'contextKey' => $contextKey,
            'sousGroupe_compact_value' => $compact_value,
            'sousGroupes_permissions' => $sousGroupes_permissions,
            'sousGroupes_permissionsByItem' => $sousGroupes_permissionsByItem
        ];
    }

}
