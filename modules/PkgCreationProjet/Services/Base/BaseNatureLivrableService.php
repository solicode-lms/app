<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Modules\PkgCreationProjet\Models\NatureLivrable;
use Modules\Core\Services\BaseService;

/**
 * Classe NatureLivrableService pour gérer la persistance de l'entité NatureLivrable.
 */
class BaseNatureLivrableService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour natureLivrables.
     *
     * @var array
     */
    protected $fieldsSearchable = [
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
     * Constructeur de la classe NatureLivrableService.
     */
    public function __construct()
    {
        parent::__construct(new NatureLivrable());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::natureLivrable.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('natureLivrable');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de natureLivrable.
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
    public function getNatureLivrableStats(): array
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
            'table' => 'PkgCreationProjet::natureLivrable._table',
            default => 'PkgCreationProjet::natureLivrable._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('natureLivrable_view_type', $default_view_type);
        $natureLivrable_viewType = $this->viewState->get('natureLivrable_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('natureLivrable_view_type') === 'widgets') {
            $this->viewState->set("filter.natureLivrable.visible", 1);
        }
        
        // Récupération des données
        $natureLivrables_data = $this->paginate($params);
        $natureLivrables_stats = $this->getnatureLivrableStats();
        $natureLivrables_filters = $this->getFieldsFilterable();
        $natureLivrable_instance = $this->createInstance();
        $natureLivrable_viewTypes = $this->getViewTypes();
        $natureLivrable_partialViewName = $this->getPartialViewName($natureLivrable_viewType);
        $natureLivrable_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.natureLivrable.stats', $natureLivrables_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'natureLivrable_viewTypes',
            'natureLivrable_viewType',
            'natureLivrables_data',
            'natureLivrables_stats',
            'natureLivrables_filters',
            'natureLivrable_instance',
            'natureLivrable_title',
            'contextKey'
        );
    
        return [
            'natureLivrables_data' => $natureLivrables_data,
            'natureLivrables_stats' => $natureLivrables_stats,
            'natureLivrables_filters' => $natureLivrables_filters,
            'natureLivrable_instance' => $natureLivrable_instance,
            'natureLivrable_viewType' => $natureLivrable_viewType,
            'natureLivrable_viewTypes' => $natureLivrable_viewTypes,
            'natureLivrable_partialViewName' => $natureLivrable_partialViewName,
            'contextKey' => $contextKey,
            'natureLivrable_compact_value' => $compact_value
        ];
    }

}
