<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgFormation\Models\AnneeFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe AnneeFormationService pour gérer la persistance de l'entité AnneeFormation.
 */
class BaseAnneeFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour anneeFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
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
     * Constructeur de la classe AnneeFormationService.
     */
    public function __construct()
    {
        parent::__construct(new AnneeFormation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::anneeFormation.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('anneeFormation');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de anneeFormation.
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
    public function getAnneeFormationStats(): array
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
            'table' => 'PkgFormation::anneeFormation._table',
            default => 'PkgFormation::anneeFormation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('anneeFormation_view_type', $default_view_type);
        $anneeFormation_viewType = $this->viewState->get('anneeFormation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('anneeFormation_view_type') === 'widgets') {
            $this->viewState->set("scope.anneeFormation.visible", 1);
        }else{
            $this->viewState->remove("scope.anneeFormation.visible");
        }
        
        // Récupération des données
        $anneeFormations_data = $this->paginate($params);
        $anneeFormations_stats = $this->getanneeFormationStats();
        $anneeFormations_total = collect($anneeFormations_stats)->firstWhere('code', 'total')['value'] ?? null;
        $anneeFormations_filters = $this->getFieldsFilterable();
        $anneeFormation_instance = $this->createInstance();
        $anneeFormation_viewTypes = $this->getViewTypes();
        $anneeFormation_partialViewName = $this->getPartialViewName($anneeFormation_viewType);
        $anneeFormation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.anneeFormation.stats', $anneeFormations_stats);
    
        $anneeFormations_permissions = [

            'edit-anneeFormation' => Auth::user()->can('edit-anneeFormation'),
            'destroy-anneeFormation' => Auth::user()->can('destroy-anneeFormation'),
            'show-anneeFormation' => Auth::user()->can('show-anneeFormation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $anneeFormations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($anneeFormations_data as $item) {
                $anneeFormations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'anneeFormation_viewTypes',
            'anneeFormation_viewType',
            'anneeFormations_data',
            'anneeFormations_stats',
            'anneeFormations_total',
            'anneeFormations_filters',
            'anneeFormation_instance',
            'anneeFormation_title',
            'contextKey',
            'anneeFormations_permissions',
            'anneeFormations_permissionsByItem'
        );
    
        return [
            'anneeFormations_data' => $anneeFormations_data,
            'anneeFormations_stats' => $anneeFormations_stats,
            'anneeFormations_total' => $anneeFormations_total,
            'anneeFormations_filters' => $anneeFormations_filters,
            'anneeFormation_instance' => $anneeFormation_instance,
            'anneeFormation_viewType' => $anneeFormation_viewType,
            'anneeFormation_viewTypes' => $anneeFormation_viewTypes,
            'anneeFormation_partialViewName' => $anneeFormation_partialViewName,
            'contextKey' => $contextKey,
            'anneeFormation_compact_value' => $compact_value,
            'anneeFormations_permissions' => $anneeFormations_permissions,
            'anneeFormations_permissionsByItem' => $anneeFormations_permissionsByItem
        ];
    }

}
