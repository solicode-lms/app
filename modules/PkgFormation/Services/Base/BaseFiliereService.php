<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Modules\PkgFormation\Models\Filiere;
use Modules\Core\Services\BaseService;

/**
 * Classe FiliereService pour gérer la persistance de l'entité Filiere.
 */
class BaseFiliereService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour filieres.
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
     * Constructeur de la classe FiliereService.
     */
    public function __construct()
    {
        parent::__construct(new Filiere());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::filiere.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('filiere');
        $this->fieldsFilterable = [];
    

    }

    /**
     * Crée une nouvelle instance de filiere.
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
    public function getFiliereStats(): array
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
            'table' => 'PkgFormation::filiere._table',
            default => 'PkgFormation::filiere._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('filiere_view_type', $default_view_type);
        $filiere_viewType = $this->viewState->get('filiere_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('filiere_view_type') === 'widgets') {
            $this->viewState->set("scope.filiere.visible", 1);
        }else{
            $this->viewState->remove("scope.filiere.visible");
        }
        
        // Récupération des données
        $filieres_data = $this->paginate($params);
        $filieres_stats = $this->getfiliereStats();
        $filieres_filters = $this->getFieldsFilterable();
        $filiere_instance = $this->createInstance();
        $filiere_viewTypes = $this->getViewTypes();
        $filiere_partialViewName = $this->getPartialViewName($filiere_viewType);
        $filiere_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.filiere.stats', $filieres_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'filiere_viewTypes',
            'filiere_viewType',
            'filieres_data',
            'filieres_stats',
            'filieres_filters',
            'filiere_instance',
            'filiere_title',
            'contextKey'
        );
    
        return [
            'filieres_data' => $filieres_data,
            'filieres_stats' => $filieres_stats,
            'filieres_filters' => $filieres_filters,
            'filiere_instance' => $filiere_instance,
            'filiere_viewType' => $filiere_viewType,
            'filiere_viewTypes' => $filiere_viewTypes,
            'filiere_partialViewName' => $filiere_partialViewName,
            'contextKey' => $contextKey,
            'filiere_compact_value' => $compact_value
        ];
    }

}
