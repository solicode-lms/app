<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgGestionTaches\Models\RealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class BaseRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'tache_id',
        'realisation_projet_id',
        'dateDebut',
        'dateFin',
        'remarque_evaluateur',
        'etat_realisation_tache_id',
        'note',
        'remarques_formateur',
        'remarques_apprenant'
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
     * Constructeur de la classe RealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgGestionTaches::realisationTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::tache.plural"), 'tache_id', \Modules\PkgGestionTaches\Models\Tache::class, 'titre');
        }

        if (!array_key_exists('realisation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::realisationProjet.plural"), 'realisation_projet_id', \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 'id');
        }

        if (!array_key_exists('etat_realisation_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgGestionTaches::etatRealisationTache.plural"), 'etat_realisation_tache_id', \Modules\PkgGestionTaches\Models\EtatRealisationTache::class, 'nom');
        }

    }

    /**
     * Crée une nouvelle instance de realisationTache.
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
    public function getRealisationTacheStats(): array
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
                'icon'  => 'fas fa-table',
            ],
            [
                'type'  => 'table-evaluation',
                'label' => 'Vue évaluation',
                'icon'  => 'fas fa-clipboard-check',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgGestionTaches::realisationTache._table',
            'table-evaluation' => 'PkgGestionTaches::realisationTache._table-evaluation',
            default => 'PkgGestionTaches::realisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationTache_view_type', $default_view_type);
        $realisationTache_viewType = $this->viewState->get('realisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationTache.visible");
        }
        
        // Récupération des données
        $realisationTaches_data = $this->paginate($params);
        $realisationTaches_stats = $this->getrealisationTacheStats();
        $realisationTaches_filters = $this->getFieldsFilterable();
        $realisationTache_instance = $this->createInstance();
        $realisationTache_viewTypes = $this->getViewTypes();
        $realisationTache_partialViewName = $this->getPartialViewName($realisationTache_viewType);
        $realisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationTache.stats', $realisationTaches_stats);
    
        $realisationTaches_permissions = [
            'index-livrablesRealisation' => Auth::user()->can('index-livrablesRealisation'),
            'show-projet' => Auth::user()->can('show-projet'),

            'edit-realisationTache' => Auth::user()->can('edit-realisationTache'),
            'destroy-realisationTache' => Auth::user()->can('destroy-realisationTache'),
            'show-realisationTache' => Auth::user()->can('show-realisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationTaches_data as $item) {
                $realisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationTache_viewTypes',
            'realisationTache_viewType',
            'realisationTaches_data',
            'realisationTaches_stats',
            'realisationTaches_filters',
            'realisationTache_instance',
            'realisationTache_title',
            'contextKey',
            'realisationTaches_permissions',
            'realisationTaches_permissionsByItem'
        );
    
        return [
            'realisationTaches_data' => $realisationTaches_data,
            'realisationTaches_stats' => $realisationTaches_stats,
            'realisationTaches_filters' => $realisationTaches_filters,
            'realisationTache_instance' => $realisationTache_instance,
            'realisationTache_viewType' => $realisationTache_viewType,
            'realisationTache_viewTypes' => $realisationTache_viewTypes,
            'realisationTache_partialViewName' => $realisationTache_partialViewName,
            'contextKey' => $contextKey,
            'realisationTache_compact_value' => $compact_value,
            'realisationTaches_permissions' => $realisationTaches_permissions,
            'realisationTaches_permissionsByItem' => $realisationTaches_permissionsByItem
        ];
    }

}
