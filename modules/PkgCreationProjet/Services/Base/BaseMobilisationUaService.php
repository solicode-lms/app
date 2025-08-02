<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\Core\Services\BaseService;

/**
 * Classe MobilisationUaService pour gérer la persistance de l'entité MobilisationUa.
 */
class BaseMobilisationUaService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour mobilisationUas.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'unite_apprentissage_id',
        'bareme_evaluation_prototype',
        'criteres_evaluation_prototype',
        'bareme_evaluation_projet',
        'criteres_evaluation_projet',
        'description',
        'projet_id'
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
     * Constructeur de la classe MobilisationUaService.
     */
    public function __construct()
    {
        parent::__construct(new MobilisationUa());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::mobilisationUa.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('mobilisationUa');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {


                    $uniteApprentissageService = new \Modules\PkgCompetences\Services\UniteApprentissageService();
                    $uniteApprentissageIds = $this->getAvailableFilterValues('unite_apprentissage_id');
                    $uniteApprentissages = $uniteApprentissageService->getByIds($uniteApprentissageIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::uniteApprentissage.plural"), 
                        'unite_apprentissage_id', 
                        \Modules\PkgCompetences\Models\UniteApprentissage::class, 
                        'code',
                        $uniteApprentissages
                    );
                }
            
            
                if (!array_key_exists('projet_id', $scopeVariables)) {


                    $projetService = new \Modules\PkgCreationProjet\Services\ProjetService();
                    $projetIds = $this->getAvailableFilterValues('projet_id');
                    $projets = $projetService->getByIds($projetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::projet.plural"), 
                        'projet_id', 
                        \Modules\PkgCreationProjet\Models\Projet::class, 
                        'titre',
                        $projets
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de mobilisationUa.
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
    public function getMobilisationUaStats(): array
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
            'table' => 'PkgCreationProjet::mobilisationUa._table',
            default => 'PkgCreationProjet::mobilisationUa._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('mobilisationUa_view_type', $default_view_type);
        $mobilisationUa_viewType = $this->viewState->get('mobilisationUa_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('mobilisationUa_view_type') === 'widgets') {
            $this->viewState->set("scope.mobilisationUa.visible", 1);
        }else{
            $this->viewState->remove("scope.mobilisationUa.visible");
        }
        
        // Récupération des données
        $mobilisationUas_data = $this->paginate($params);
        $mobilisationUas_stats = $this->getmobilisationUaStats();
        $mobilisationUas_total = collect($mobilisationUas_stats)->firstWhere('code', 'total')['value'] ?? null;
        $mobilisationUas_filters = $this->getFieldsFilterable();
        $mobilisationUa_instance = $this->createInstance();
        $mobilisationUa_viewTypes = $this->getViewTypes();
        $mobilisationUa_partialViewName = $this->getPartialViewName($mobilisationUa_viewType);
        $mobilisationUa_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.mobilisationUa.stats', $mobilisationUas_stats);
    
        $mobilisationUas_permissions = [

            'edit-mobilisationUa' => Auth::user()->can('edit-mobilisationUa'),
            'destroy-mobilisationUa' => Auth::user()->can('destroy-mobilisationUa'),
            'show-mobilisationUa' => Auth::user()->can('show-mobilisationUa'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $mobilisationUas_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($mobilisationUas_data as $item) {
                $mobilisationUas_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'mobilisationUa_viewTypes',
            'mobilisationUa_viewType',
            'mobilisationUas_data',
            'mobilisationUas_stats',
            'mobilisationUas_total',
            'mobilisationUas_filters',
            'mobilisationUa_instance',
            'mobilisationUa_title',
            'contextKey',
            'mobilisationUas_permissions',
            'mobilisationUas_permissionsByItem'
        );
    
        return [
            'mobilisationUas_data' => $mobilisationUas_data,
            'mobilisationUas_stats' => $mobilisationUas_stats,
            'mobilisationUas_total' => $mobilisationUas_total,
            'mobilisationUas_filters' => $mobilisationUas_filters,
            'mobilisationUa_instance' => $mobilisationUa_instance,
            'mobilisationUa_viewType' => $mobilisationUa_viewType,
            'mobilisationUa_viewTypes' => $mobilisationUa_viewTypes,
            'mobilisationUa_partialViewName' => $mobilisationUa_partialViewName,
            'contextKey' => $contextKey,
            'mobilisationUa_compact_value' => $compact_value,
            'mobilisationUas_permissions' => $mobilisationUas_permissions,
            'mobilisationUas_permissionsByItem' => $mobilisationUas_permissionsByItem
        ];
    }

}
