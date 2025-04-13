<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\NiveauCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe NiveauCompetenceService pour gérer la persistance de l'entité NiveauCompetence.
 */
class BaseNiveauCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveauCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'description',
        'competence_id'
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
     * Constructeur de la classe NiveauCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new NiveauCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::niveauCompetence.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('niveauCompetence');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('competence_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::competence.plural"), 'competence_id', \Modules\PkgCompetences\Models\Competence::class, 'code');
        }

    }

    /**
     * Crée une nouvelle instance de niveauCompetence.
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
    public function getNiveauCompetenceStats(): array
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
            'table' => 'PkgCompetences::niveauCompetence._table',
            default => 'PkgCompetences::niveauCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('niveauCompetence_view_type', $default_view_type);
        $niveauCompetence_viewType = $this->viewState->get('niveauCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('niveauCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.niveauCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.niveauCompetence.visible");
        }
        
        // Récupération des données
        $niveauCompetences_data = $this->paginate($params);
        $niveauCompetences_stats = $this->getniveauCompetenceStats();
        $niveauCompetences_filters = $this->getFieldsFilterable();
        $niveauCompetence_instance = $this->createInstance();
        $niveauCompetence_viewTypes = $this->getViewTypes();
        $niveauCompetence_partialViewName = $this->getPartialViewName($niveauCompetence_viewType);
        $niveauCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.niveauCompetence.stats', $niveauCompetences_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'niveauCompetence_viewTypes',
            'niveauCompetence_viewType',
            'niveauCompetences_data',
            'niveauCompetences_stats',
            'niveauCompetences_filters',
            'niveauCompetence_instance',
            'niveauCompetence_title',
            'contextKey'
        );
    
        return [
            'niveauCompetences_data' => $niveauCompetences_data,
            'niveauCompetences_stats' => $niveauCompetences_stats,
            'niveauCompetences_filters' => $niveauCompetences_filters,
            'niveauCompetence_instance' => $niveauCompetence_instance,
            'niveauCompetence_viewType' => $niveauCompetence_viewType,
            'niveauCompetence_viewTypes' => $niveauCompetence_viewTypes,
            'niveauCompetence_partialViewName' => $niveauCompetence_partialViewName,
            'contextKey' => $contextKey,
            'niveauCompetence_compact_value' => $compact_value
        ];
    }

}
