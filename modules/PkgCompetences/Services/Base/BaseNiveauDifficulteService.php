<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Modules\PkgCompetences\Models\NiveauDifficulte;
use Modules\Core\Services\BaseService;

/**
 * Classe NiveauDifficulteService pour gérer la persistance de l'entité NiveauDifficulte.
 */
class BaseNiveauDifficulteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveauDifficultes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'noteMin',
        'noteMax',
        'formateur_id',
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
     * Constructeur de la classe NiveauDifficulteService.
     */
    public function __construct()
    {
        parent::__construct(new NiveauDifficulte());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::niveauDifficulte.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('niveauDifficulte');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }


    }

    /**
     * Crée une nouvelle instance de niveauDifficulte.
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
    public function getNiveauDifficulteStats(): array
    {

        $stats = $this->initStats();

        // Ajouter les statistiques du propriétaire
        //$contexteState = $this->getContextState();
        // if ($contexteState !== null) {
        //     $stats[] = $contexteState;
        // }
        

        return $stats;
    }

    public function getContextState()
    {
        $value = $this->viewState->generateTitleFromVariables();
        return [
                "icon" => "fas fa-filter",
                "label" => "Filtre",
                "value" =>  $value
        ];
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
            'table' => 'PkgCompetences::niveauDifficulte._table',
            default => 'PkgCompetences::niveauDifficulte._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('niveauDifficulte_view_type', $default_view_type);
        $niveauDifficulte_viewType = $this->viewState->get('niveauDifficulte_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('niveauDifficulte_view_type') === 'widgets') {
            $this->viewState->set("filter.niveauDifficulte.visible", 1);
        }
        
        // Récupération des données
        $niveauDifficultes_data = $this->paginate($params);
        $niveauDifficultes_stats = $this->getniveauDifficulteStats();
        $niveauDifficultes_filters = $this->getFieldsFilterable();
        $niveauDifficulte_instance = $this->createInstance();
        $niveauDifficulte_viewTypes = $this->getViewTypes();
        $niveauDifficulte_partialViewName = $this->getPartialViewName($niveauDifficulte_viewType);
        $niveauDifficulte_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.niveauDifficulte.stats', $niveauDifficultes_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'niveauDifficulte_viewTypes',
            'niveauDifficulte_viewType',
            'niveauDifficultes_data',
            'niveauDifficultes_stats',
            'niveauDifficultes_filters',
            'niveauDifficulte_instance',
            'niveauDifficulte_title',
            'contextKey'
        );
    
        return [
            'niveauDifficultes_data' => $niveauDifficultes_data,
            'niveauDifficultes_stats' => $niveauDifficultes_stats,
            'niveauDifficultes_filters' => $niveauDifficultes_filters,
            'niveauDifficulte_instance' => $niveauDifficulte_instance,
            'niveauDifficulte_viewType' => $niveauDifficulte_viewType,
            'niveauDifficulte_viewTypes' => $niveauDifficulte_viewTypes,
            'niveauDifficulte_partialViewName' => $niveauDifficulte_partialViewName,
            'contextKey' => $contextKey,
            'niveauDifficulte_compact_value' => $compact_value
        ];
    }

}
