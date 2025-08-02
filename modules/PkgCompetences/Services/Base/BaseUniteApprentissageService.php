<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCompetences\Models\UniteApprentissage;
use Modules\Core\Services\BaseService;

/**
 * Classe UniteApprentissageService pour gérer la persistance de l'entité UniteApprentissage.
 */
class BaseUniteApprentissageService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour uniteApprentissages.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'nom',
        'micro_competence_id',
        'lien',
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
     * Constructeur de la classe UniteApprentissageService.
     */
    public function __construct()
    {
        parent::__construct(new UniteApprentissage());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::uniteApprentissage.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('uniteApprentissage');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('micro_competence_id', $scopeVariables)) {


                    $microCompetenceService = new \Modules\PkgCompetences\Services\MicroCompetenceService();
                    $microCompetenceIds = $this->getAvailableFilterValues('micro_competence_id');
                    $microCompetences = $microCompetenceService->getByIds($microCompetenceIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::microCompetence.plural"), 
                        'micro_competence_id', 
                        \Modules\PkgCompetences\Models\MicroCompetence::class, 
                        'titre',
                        $microCompetences
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de uniteApprentissage.
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
    public function getUniteApprentissageStats(): array
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
            'table' => 'PkgCompetences::uniteApprentissage._table',
            default => 'PkgCompetences::uniteApprentissage._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('uniteApprentissage_view_type', $default_view_type);
        $uniteApprentissage_viewType = $this->viewState->get('uniteApprentissage_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('uniteApprentissage_view_type') === 'widgets') {
            $this->viewState->set("scope.uniteApprentissage.visible", 1);
        }else{
            $this->viewState->remove("scope.uniteApprentissage.visible");
        }
        
        // Récupération des données
        $uniteApprentissages_data = $this->paginate($params);
        $uniteApprentissages_stats = $this->getuniteApprentissageStats();
        $uniteApprentissages_total = collect($uniteApprentissages_stats)->firstWhere('code', 'total')['value'] ?? null;
        $uniteApprentissages_filters = $this->getFieldsFilterable();
        $uniteApprentissage_instance = $this->createInstance();
        $uniteApprentissage_viewTypes = $this->getViewTypes();
        $uniteApprentissage_partialViewName = $this->getPartialViewName($uniteApprentissage_viewType);
        $uniteApprentissage_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.uniteApprentissage.stats', $uniteApprentissages_stats);
    
        $uniteApprentissages_permissions = [

            'edit-uniteApprentissage' => Auth::user()->can('edit-uniteApprentissage'),
            'destroy-uniteApprentissage' => Auth::user()->can('destroy-uniteApprentissage'),
            'show-uniteApprentissage' => Auth::user()->can('show-uniteApprentissage'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $uniteApprentissages_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($uniteApprentissages_data as $item) {
                $uniteApprentissages_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'uniteApprentissage_viewTypes',
            'uniteApprentissage_viewType',
            'uniteApprentissages_data',
            'uniteApprentissages_stats',
            'uniteApprentissages_total',
            'uniteApprentissages_filters',
            'uniteApprentissage_instance',
            'uniteApprentissage_title',
            'contextKey',
            'uniteApprentissages_permissions',
            'uniteApprentissages_permissionsByItem'
        );
    
        return [
            'uniteApprentissages_data' => $uniteApprentissages_data,
            'uniteApprentissages_stats' => $uniteApprentissages_stats,
            'uniteApprentissages_total' => $uniteApprentissages_total,
            'uniteApprentissages_filters' => $uniteApprentissages_filters,
            'uniteApprentissage_instance' => $uniteApprentissage_instance,
            'uniteApprentissage_viewType' => $uniteApprentissage_viewType,
            'uniteApprentissage_viewTypes' => $uniteApprentissage_viewTypes,
            'uniteApprentissage_partialViewName' => $uniteApprentissage_partialViewName,
            'contextKey' => $contextKey,
            'uniteApprentissage_compact_value' => $compact_value,
            'uniteApprentissages_permissions' => $uniteApprentissages_permissions,
            'uniteApprentissages_permissionsByItem' => $uniteApprentissages_permissionsByItem
        ];
    }

}
