<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\Services\Base;

use Modules\PkgValidationProjets\Models\Evaluateur;
use Modules\Core\Services\BaseService;

/**
 * Classe EvaluateurService pour gérer la persistance de l'entité Evaluateur.
 */
class BaseEvaluateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour evaluateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'prenom',
        'email',
        'organism',
        'telephone',
        'user_id'
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
     * Constructeur de la classe EvaluateurService.
     */
    public function __construct()
    {
        parent::__construct(new Evaluateur());
        $this->fieldsFilterable = [];
        $this->title = __('PkgValidationProjets::evaluateur.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('evaluateur');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('user_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutorisation::user.plural"), 'user_id', \Modules\PkgAutorisation\Models\User::class, 'name');
        }

    }

    /**
     * Crée une nouvelle instance de evaluateur.
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
    public function getEvaluateurStats(): array
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
            'table' => 'PkgValidationProjets::evaluateur._table',
            default => 'PkgValidationProjets::evaluateur._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('evaluateur_view_type', $default_view_type);
        $evaluateur_viewType = $this->viewState->get('evaluateur_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('evaluateur_view_type') === 'widgets') {
            $this->viewState->set("scope.evaluateur.visible", 1);
        }else{
            $this->viewState->remove("scope.evaluateur.visible");
        }
        
        // Récupération des données
        $evaluateurs_data = $this->paginate($params);
        $evaluateurs_stats = $this->getevaluateurStats();
        $evaluateurs_filters = $this->getFieldsFilterable();
        $evaluateur_instance = $this->createInstance();
        $evaluateur_viewTypes = $this->getViewTypes();
        $evaluateur_partialViewName = $this->getPartialViewName($evaluateur_viewType);
        $evaluateur_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.evaluateur.stats', $evaluateurs_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'evaluateur_viewTypes',
            'evaluateur_viewType',
            'evaluateurs_data',
            'evaluateurs_stats',
            'evaluateurs_filters',
            'evaluateur_instance',
            'evaluateur_title',
            'contextKey'
        );
    
        return [
            'evaluateurs_data' => $evaluateurs_data,
            'evaluateurs_stats' => $evaluateurs_stats,
            'evaluateurs_filters' => $evaluateurs_filters,
            'evaluateur_instance' => $evaluateur_instance,
            'evaluateur_viewType' => $evaluateur_viewType,
            'evaluateur_viewTypes' => $evaluateur_viewTypes,
            'evaluateur_partialViewName' => $evaluateur_partialViewName,
            'contextKey' => $contextKey,
            'evaluateur_compact_value' => $compact_value
        ];
    }

}
