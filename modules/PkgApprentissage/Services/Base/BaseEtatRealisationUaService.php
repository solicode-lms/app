<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatRealisationUaService pour gérer la persistance de l'entité EtatRealisationUa.
 */
class BaseEtatRealisationUaService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationUas.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'code',
        'sys_color_id',
        'is_editable_only_by_formateur',
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
     * Constructeur de la classe EtatRealisationUaService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationUa());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::etatRealisationUa.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationUa');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('sys_color_id', $scopeVariables)) {


                    $sysColorService = new \Modules\Core\Services\SysColorService();
                    $sysColorIds = $this->getAvailableFilterValues('sys_color_id');
                    $sysColors = $sysColorService->getByIds($sysColorIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysColor.plural"), 
                        'sys_color_id', 
                        \Modules\Core\Models\SysColor::class, 
                        'name',
                        $sysColors
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de etatRealisationUa.
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
    public function getEtatRealisationUaStats(): array
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
            'table' => 'PkgApprentissage::etatRealisationUa._table',
            default => 'PkgApprentissage::etatRealisationUa._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationUa_view_type', $default_view_type);
        $etatRealisationUa_viewType = $this->viewState->get('etatRealisationUa_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationUa_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationUa.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationUa.visible");
        }
        
        // Récupération des données
        $etatRealisationUas_data = $this->paginate($params);
        $etatRealisationUas_stats = $this->getetatRealisationUaStats();
        $etatRealisationUas_total = $this->count();
        $etatRealisationUas_filters = $this->getFieldsFilterable();
        $etatRealisationUa_instance = $this->createInstance();
        $etatRealisationUa_viewTypes = $this->getViewTypes();
        $etatRealisationUa_partialViewName = $this->getPartialViewName($etatRealisationUa_viewType);
        $etatRealisationUa_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationUa.stats', $etatRealisationUas_stats);
    
        $etatRealisationUas_permissions = [

            'edit-etatRealisationUa' => Auth::user()->can('edit-etatRealisationUa'),
            'destroy-etatRealisationUa' => Auth::user()->can('destroy-etatRealisationUa'),
            'show-etatRealisationUa' => Auth::user()->can('show-etatRealisationUa'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationUas_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationUas_data as $item) {
                $etatRealisationUas_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationUa_viewTypes',
            'etatRealisationUa_viewType',
            'etatRealisationUas_data',
            'etatRealisationUas_stats',
            'etatRealisationUas_total',
            'etatRealisationUas_filters',
            'etatRealisationUa_instance',
            'etatRealisationUa_title',
            'contextKey',
            'etatRealisationUas_permissions',
            'etatRealisationUas_permissionsByItem'
        );
    
        return [
            'etatRealisationUas_data' => $etatRealisationUas_data,
            'etatRealisationUas_stats' => $etatRealisationUas_stats,
            'etatRealisationUas_total' => $etatRealisationUas_total,
            'etatRealisationUas_filters' => $etatRealisationUas_filters,
            'etatRealisationUa_instance' => $etatRealisationUa_instance,
            'etatRealisationUa_viewType' => $etatRealisationUa_viewType,
            'etatRealisationUa_viewTypes' => $etatRealisationUa_viewTypes,
            'etatRealisationUa_partialViewName' => $etatRealisationUa_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationUa_compact_value' => $compact_value,
            'etatRealisationUas_permissions' => $etatRealisationUas_permissions,
            'etatRealisationUas_permissionsByItem' => $etatRealisationUas_permissionsByItem
        ];
    }

}
