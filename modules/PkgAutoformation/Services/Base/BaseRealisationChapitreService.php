<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgAutoformation\Models\RealisationChapitre;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationChapitreService pour gérer la persistance de l'entité RealisationChapitre.
 */
class BaseRealisationChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationChapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'date_debut',
        'date_fin',
        'chapitre_id',
        'realisation_formation_id',
        'etat_chapitre_id'
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
     * Constructeur de la classe RealisationChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationChapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgAutoformation::realisationChapitre.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationChapitre');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('chapitre_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::chapitre.plural"), 'chapitre_id', \Modules\PkgAutoformation\Models\Chapitre::class, 'nom');
        }

        if (!array_key_exists('realisation_formation_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::realisationFormation.plural"), 'realisation_formation_id', \Modules\PkgAutoformation\Models\RealisationFormation::class, 'id');
        }

        if (!array_key_exists('etat_chapitre_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::etatChapitre.plural"), 'etat_chapitre_id', \Modules\PkgAutoformation\Models\EtatChapitre::class, 'nom');
        }

    }

    /**
     * Crée une nouvelle instance de realisationChapitre.
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
    public function getRealisationChapitreStats(): array
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
            'table' => 'PkgAutoformation::realisationChapitre._table',
            default => 'PkgAutoformation::realisationChapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationChapitre_view_type', $default_view_type);
        $realisationChapitre_viewType = $this->viewState->get('realisationChapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationChapitre_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationChapitre.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationChapitre.visible");
        }
        
        // Récupération des données
        $realisationChapitres_data = $this->paginate($params);
        $realisationChapitres_stats = $this->getrealisationChapitreStats();
        $realisationChapitres_filters = $this->getFieldsFilterable();
        $realisationChapitre_instance = $this->createInstance();
        $realisationChapitre_viewTypes = $this->getViewTypes();
        $realisationChapitre_partialViewName = $this->getPartialViewName($realisationChapitre_viewType);
        $realisationChapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationChapitre.stats', $realisationChapitres_stats);
    
        $realisationChapitres_permissions = [

            'edit-realisationChapitre' => Auth::user()->can('edit-realisationChapitre'),
            'destroy-realisationChapitre' => Auth::user()->can('destroy-realisationChapitre'),
            'show-realisationChapitre' => Auth::user()->can('show-realisationChapitre'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationChapitres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationChapitres_data as $item) {
                $realisationChapitres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationChapitre_viewTypes',
            'realisationChapitre_viewType',
            'realisationChapitres_data',
            'realisationChapitres_stats',
            'realisationChapitres_filters',
            'realisationChapitre_instance',
            'realisationChapitre_title',
            'contextKey',
            'realisationChapitres_permissions',
            'realisationChapitres_permissionsByItem'
        );
    
        return [
            'realisationChapitres_data' => $realisationChapitres_data,
            'realisationChapitres_stats' => $realisationChapitres_stats,
            'realisationChapitres_filters' => $realisationChapitres_filters,
            'realisationChapitre_instance' => $realisationChapitre_instance,
            'realisationChapitre_viewType' => $realisationChapitre_viewType,
            'realisationChapitre_viewTypes' => $realisationChapitre_viewTypes,
            'realisationChapitre_partialViewName' => $realisationChapitre_partialViewName,
            'contextKey' => $contextKey,
            'realisationChapitre_compact_value' => $compact_value,
            'realisationChapitres_permissions' => $realisationChapitres_permissions,
            'realisationChapitres_permissionsByItem' => $realisationChapitres_permissionsByItem
        ];
    }

}
