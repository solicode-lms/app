<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\Services\Base;

use Modules\PkgValidationProjets\Models\EtatEvaluationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatEvaluationProjetService pour gérer la persistance de l'entité EtatEvaluationProjet.
 */
class BaseEtatEvaluationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatEvaluationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'titre',
        'description',
        'sys_color_id'
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
     * Constructeur de la classe EtatEvaluationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EtatEvaluationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgValidationProjets::etatEvaluationProjet.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatEvaluationProjet');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }

    }

    /**
     * Crée une nouvelle instance de etatEvaluationProjet.
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
    public function getEtatEvaluationProjetStats(): array
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
            'table' => 'PkgValidationProjets::etatEvaluationProjet._table',
            default => 'PkgValidationProjets::etatEvaluationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatEvaluationProjet_view_type', $default_view_type);
        $etatEvaluationProjet_viewType = $this->viewState->get('etatEvaluationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatEvaluationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.etatEvaluationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.etatEvaluationProjet.visible");
        }
        
        // Récupération des données
        $etatEvaluationProjets_data = $this->paginate($params);
        $etatEvaluationProjets_stats = $this->getetatEvaluationProjetStats();
        $etatEvaluationProjets_filters = $this->getFieldsFilterable();
        $etatEvaluationProjet_instance = $this->createInstance();
        $etatEvaluationProjet_viewTypes = $this->getViewTypes();
        $etatEvaluationProjet_partialViewName = $this->getPartialViewName($etatEvaluationProjet_viewType);
        $etatEvaluationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatEvaluationProjet.stats', $etatEvaluationProjets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatEvaluationProjet_viewTypes',
            'etatEvaluationProjet_viewType',
            'etatEvaluationProjets_data',
            'etatEvaluationProjets_stats',
            'etatEvaluationProjets_filters',
            'etatEvaluationProjet_instance',
            'etatEvaluationProjet_title',
            'contextKey'
        );
    
        return [
            'etatEvaluationProjets_data' => $etatEvaluationProjets_data,
            'etatEvaluationProjets_stats' => $etatEvaluationProjets_stats,
            'etatEvaluationProjets_filters' => $etatEvaluationProjets_filters,
            'etatEvaluationProjet_instance' => $etatEvaluationProjet_instance,
            'etatEvaluationProjet_viewType' => $etatEvaluationProjet_viewType,
            'etatEvaluationProjet_viewTypes' => $etatEvaluationProjet_viewTypes,
            'etatEvaluationProjet_partialViewName' => $etatEvaluationProjet_partialViewName,
            'contextKey' => $contextKey,
            'etatEvaluationProjet_compact_value' => $compact_value
        ];
    }

}
