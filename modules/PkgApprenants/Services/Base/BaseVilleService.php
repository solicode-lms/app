<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprenants\Models\Ville;
use Modules\Core\Services\BaseService;

/**
 * Classe VilleService pour gérer la persistance de l'entité Ville.
 */
class BaseVilleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour villes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom'
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
     * Constructeur de la classe VilleService.
     */
    public function __construct()
    {
        parent::__construct(new Ville());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::ville.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('ville');
        $this->fieldsFilterable = [];
    

    }

    /**
     * Crée une nouvelle instance de ville.
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
    public function getVilleStats(): array
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
            'table' => 'PkgApprenants::ville._table',
            default => 'PkgApprenants::ville._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('ville_view_type', $default_view_type);
        $ville_viewType = $this->viewState->get('ville_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('ville_view_type') === 'widgets') {
            $this->viewState->set("scope.ville.visible", 1);
        }else{
            $this->viewState->remove("scope.ville.visible");
        }
        
        // Récupération des données
        $villes_data = $this->paginate($params);
        $villes_stats = $this->getvilleStats();
        $villes_filters = $this->getFieldsFilterable();
        $ville_instance = $this->createInstance();
        $ville_viewTypes = $this->getViewTypes();
        $ville_partialViewName = $this->getPartialViewName($ville_viewType);
        $ville_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.ville.stats', $villes_stats);
    
        $villes_permissions = [

            'edit-ville' => Auth::user()->can('edit-ville'),
            'destroy-ville' => Auth::user()->can('destroy-ville'),
            'show-ville' => Auth::user()->can('show-ville'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $villes_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($villes_data as $item) {
                $villes_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'ville_viewTypes',
            'ville_viewType',
            'villes_data',
            'villes_stats',
            'villes_filters',
            'ville_instance',
            'ville_title',
            'contextKey',
            'villes_permissions',
            'villes_permissionsByItem'
        );
    
        return [
            'villes_data' => $villes_data,
            'villes_stats' => $villes_stats,
            'villes_filters' => $villes_filters,
            'ville_instance' => $ville_instance,
            'ville_viewType' => $ville_viewType,
            'ville_viewTypes' => $ville_viewTypes,
            'ville_partialViewName' => $ville_partialViewName,
            'contextKey' => $contextKey,
            'ville_compact_value' => $compact_value,
            'villes_permissions' => $villes_permissions,
            'villes_permissionsByItem' => $villes_permissionsByItem
        ];
    }

}
