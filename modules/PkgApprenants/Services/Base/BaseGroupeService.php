<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Modules\PkgApprenants\Models\Groupe;
use Modules\Core\Services\BaseService;

/**
 * Classe GroupeService pour gérer la persistance de l'entité Groupe.
 */
class BaseGroupeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour groupes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
        'description',
        'filiere_id',
        'annee_formation_id'
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
     * Constructeur de la classe GroupeService.
     */
    public function __construct()
    {
        parent::__construct(new Groupe());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::groupe.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('groupe');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('filiere_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::filiere.plural"), 'filiere_id', \Modules\PkgFormation\Models\Filiere::class, 'code');
        }

        if (!array_key_exists('annee_formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::anneeFormation.plural"), 'annee_formation_id', \Modules\PkgFormation\Models\AnneeFormation::class, 'titre');
        }

    }

    /**
     * Crée une nouvelle instance de groupe.
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
    public function getGroupeStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatFiliere = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Filiere::class,
                'groupes',
                'code'
            );
            $stats = array_merge($stats, $relationStatFiliere);

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
            'table' => 'PkgApprenants::groupe._table',
            default => 'PkgApprenants::groupe._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('groupe_view_type', $default_view_type);
        $groupe_viewType = $this->viewState->get('groupe_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('groupe_view_type') === 'widgets') {
            $this->viewState->set("scope.groupe.visible", 1);
        }else{
            $this->viewState->remove("scope.groupe.visible");
        }
        
        // Récupération des données
        $groupes_data = $this->paginate($params);
        $groupes_stats = $this->getgroupeStats();
        $groupes_filters = $this->getFieldsFilterable();
        $groupe_instance = $this->createInstance();
        $groupe_viewTypes = $this->getViewTypes();
        $groupe_partialViewName = $this->getPartialViewName($groupe_viewType);
        $groupe_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.groupe.stats', $groupes_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'groupe_viewTypes',
            'groupe_viewType',
            'groupes_data',
            'groupes_stats',
            'groupes_filters',
            'groupe_instance',
            'groupe_title',
            'contextKey'
        );
    
        return [
            'groupes_data' => $groupes_data,
            'groupes_stats' => $groupes_stats,
            'groupes_filters' => $groupes_filters,
            'groupe_instance' => $groupe_instance,
            'groupe_viewType' => $groupe_viewType,
            'groupe_viewTypes' => $groupe_viewTypes,
            'groupe_partialViewName' => $groupe_partialViewName,
            'contextKey' => $contextKey,
            'groupe_compact_value' => $compact_value
        ];
    }

}
