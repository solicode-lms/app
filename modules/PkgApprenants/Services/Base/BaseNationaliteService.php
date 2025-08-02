<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprenants\Models\Nationalite;
use Modules\Core\Services\BaseService;

/**
 * Classe NationaliteService pour gérer la persistance de l'entité Nationalite.
 */
class BaseNationaliteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour nationalites.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
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
     * Constructeur de la classe NationaliteService.
     */
    public function __construct()
    {
        parent::__construct(new Nationalite());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::nationalite.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('nationalite');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de nationalite.
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
    public function getNationaliteStats(): array
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
            'table' => 'PkgApprenants::nationalite._table',
            default => 'PkgApprenants::nationalite._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('nationalite_view_type', $default_view_type);
        $nationalite_viewType = $this->viewState->get('nationalite_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('nationalite_view_type') === 'widgets') {
            $this->viewState->set("scope.nationalite.visible", 1);
        }else{
            $this->viewState->remove("scope.nationalite.visible");
        }
        
        // Récupération des données
        $nationalites_data = $this->paginate($params);
        $nationalites_stats = $this->getnationaliteStats();
        $nationalites_total = collect($nationalites_stats)->firstWhere('code', 'total')['value'] ?? null;
        $nationalites_filters = $this->getFieldsFilterable();
        $nationalite_instance = $this->createInstance();
        $nationalite_viewTypes = $this->getViewTypes();
        $nationalite_partialViewName = $this->getPartialViewName($nationalite_viewType);
        $nationalite_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.nationalite.stats', $nationalites_stats);
    
        $nationalites_permissions = [

            'edit-nationalite' => Auth::user()->can('edit-nationalite'),
            'destroy-nationalite' => Auth::user()->can('destroy-nationalite'),
            'show-nationalite' => Auth::user()->can('show-nationalite'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $nationalites_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($nationalites_data as $item) {
                $nationalites_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'nationalite_viewTypes',
            'nationalite_viewType',
            'nationalites_data',
            'nationalites_stats',
            'nationalites_total',
            'nationalites_filters',
            'nationalite_instance',
            'nationalite_title',
            'contextKey',
            'nationalites_permissions',
            'nationalites_permissionsByItem'
        );
    
        return [
            'nationalites_data' => $nationalites_data,
            'nationalites_stats' => $nationalites_stats,
            'nationalites_total' => $nationalites_total,
            'nationalites_filters' => $nationalites_filters,
            'nationalite_instance' => $nationalite_instance,
            'nationalite_viewType' => $nationalite_viewType,
            'nationalite_viewTypes' => $nationalite_viewTypes,
            'nationalite_partialViewName' => $nationalite_partialViewName,
            'contextKey' => $contextKey,
            'nationalite_compact_value' => $compact_value,
            'nationalites_permissions' => $nationalites_permissions,
            'nationalites_permissionsByItem' => $nationalites_permissionsByItem
        ];
    }

}
