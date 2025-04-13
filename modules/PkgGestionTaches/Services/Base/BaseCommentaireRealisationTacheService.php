<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Modules\PkgGestionTaches\Models\CommentaireRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe CommentaireRealisationTacheService pour gérer la persistance de l'entité CommentaireRealisationTache.
 */
class BaseCommentaireRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour commentaireRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'commentaire',
        'dateCommentaire',
        'realisation_tache_id',
        'formateur_id',
        'apprenant_id'
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
     * Constructeur de la classe CommentaireRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new CommentaireRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::commentaireRealisationTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('commentaireRealisationTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::realisationTache.plural"), 'realisation_tache_id', \Modules\PkgGestionTaches\Models\RealisationTache::class, 'id');
        }



        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }



        if (!array_key_exists('apprenant_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgApprenants::apprenant.plural"), 'apprenant_id', \Modules\PkgApprenants\Models\Apprenant::class, 'nom');
        }


    }

    /**
     * Crée une nouvelle instance de commentaireRealisationTache.
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
    public function getCommentaireRealisationTacheStats(): array
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
            'table' => 'PkgGestionTaches::commentaireRealisationTache._table',
            default => 'PkgGestionTaches::commentaireRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('commentaireRealisationTache_view_type', $default_view_type);
        $commentaireRealisationTache_viewType = $this->viewState->get('commentaireRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('commentaireRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("filter.commentaireRealisationTache.visible", 1);
        }
        
        // Récupération des données
        $commentaireRealisationTaches_data = $this->paginate($params);
        $commentaireRealisationTaches_stats = $this->getcommentaireRealisationTacheStats();
        $commentaireRealisationTaches_filters = $this->getFieldsFilterable();
        $commentaireRealisationTache_instance = $this->createInstance();
        $commentaireRealisationTache_viewTypes = $this->getViewTypes();
        $commentaireRealisationTache_partialViewName = $this->getPartialViewName($commentaireRealisationTache_viewType);
        $commentaireRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.commentaireRealisationTache.stats', $commentaireRealisationTaches_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'commentaireRealisationTache_viewTypes',
            'commentaireRealisationTache_viewType',
            'commentaireRealisationTaches_data',
            'commentaireRealisationTaches_stats',
            'commentaireRealisationTaches_filters',
            'commentaireRealisationTache_instance',
            'commentaireRealisationTache_title',
            'contextKey'
        );
    
        return [
            'commentaireRealisationTaches_data' => $commentaireRealisationTaches_data,
            'commentaireRealisationTaches_stats' => $commentaireRealisationTaches_stats,
            'commentaireRealisationTaches_filters' => $commentaireRealisationTaches_filters,
            'commentaireRealisationTache_instance' => $commentaireRealisationTache_instance,
            'commentaireRealisationTache_viewType' => $commentaireRealisationTache_viewType,
            'commentaireRealisationTache_viewTypes' => $commentaireRealisationTache_viewTypes,
            'commentaireRealisationTache_partialViewName' => $commentaireRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'commentaireRealisationTache_compact_value' => $compact_value
        ];
    }

}
