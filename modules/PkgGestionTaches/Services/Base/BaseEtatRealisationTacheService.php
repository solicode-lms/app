<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatRealisationTacheService pour gérer la persistance de l'entité EtatRealisationTache.
 */
class BaseEtatRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'workflow_tache_id',
        'sys_color_id',
        'is_editable_only_by_formateur',
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
     * Constructeur de la classe EtatRealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::etatRealisationTache.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationTache');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('workflow_tache_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationTache::workflowTache.plural"), 'workflow_tache_id', \Modules\PkgRealisationTache\Models\WorkflowTache::class, 'code');
        }

        if (!array_key_exists('sys_color_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("Core::sysColor.plural"), 'sys_color_id', \Modules\Core\Models\SysColor::class, 'name');
        }

        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }

    }

    /**
     * Crée une nouvelle instance de etatRealisationTache.
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
    public function getEtatRealisationTacheStats(): array
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
            'table' => 'PkgRealisationTache::etatRealisationTache._table',
            default => 'PkgRealisationTache::etatRealisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationTache_view_type', $default_view_type);
        $etatRealisationTache_viewType = $this->viewState->get('etatRealisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationTache.visible");
        }
        
        // Récupération des données
        $etatRealisationTaches_data = $this->paginate($params);
        $etatRealisationTaches_stats = $this->getetatRealisationTacheStats();
        $etatRealisationTaches_filters = $this->getFieldsFilterable();
        $etatRealisationTache_instance = $this->createInstance();
        $etatRealisationTache_viewTypes = $this->getViewTypes();
        $etatRealisationTache_partialViewName = $this->getPartialViewName($etatRealisationTache_viewType);
        $etatRealisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationTache.stats', $etatRealisationTaches_stats);
    
        $etatRealisationTaches_permissions = [

            'edit-etatRealisationTache' => Auth::user()->can('edit-etatRealisationTache'),
            'destroy-etatRealisationTache' => Auth::user()->can('destroy-etatRealisationTache'),
            'show-etatRealisationTache' => Auth::user()->can('show-etatRealisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationTaches_data as $item) {
                $etatRealisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationTache_viewTypes',
            'etatRealisationTache_viewType',
            'etatRealisationTaches_data',
            'etatRealisationTaches_stats',
            'etatRealisationTaches_filters',
            'etatRealisationTache_instance',
            'etatRealisationTache_title',
            'contextKey',
            'etatRealisationTaches_permissions',
            'etatRealisationTaches_permissionsByItem'
        );
    
        return [
            'etatRealisationTaches_data' => $etatRealisationTaches_data,
            'etatRealisationTaches_stats' => $etatRealisationTaches_stats,
            'etatRealisationTaches_filters' => $etatRealisationTaches_filters,
            'etatRealisationTache_instance' => $etatRealisationTache_instance,
            'etatRealisationTache_viewType' => $etatRealisationTache_viewType,
            'etatRealisationTache_viewTypes' => $etatRealisationTache_viewTypes,
            'etatRealisationTache_partialViewName' => $etatRealisationTache_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationTache_compact_value' => $compact_value,
            'etatRealisationTaches_permissions' => $etatRealisationTaches_permissions,
            'etatRealisationTaches_permissionsByItem' => $etatRealisationTaches_permissionsByItem
        ];
    }

}
