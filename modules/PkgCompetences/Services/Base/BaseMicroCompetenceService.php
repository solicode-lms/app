<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCompetences\Models\MicroCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe MicroCompetenceService pour gérer la persistance de l'entité MicroCompetence.
 */
class BaseMicroCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour microCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'titre',
        'sous_titre',
        'competence_id',
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
     * Constructeur de la classe MicroCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new MicroCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::microCompetence.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('microCompetence');
        $this->fieldsFilterable = [];
        
            
                $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                $filiereIds = $this->getAvailableFilterValues('competence.module.filiere_id');
                $filieres = $filiereService->getByIds($filiereIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgFormation::filiere.plural"),
                    'competence.module.filiere_id', 
                    \Modules\PkgFormation\Models\Filiere::class,
                    "id", 
                    "id",
                    $filieres
                );
            



    }


    /**
     * Crée une nouvelle instance de microCompetence.
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
    public function getMicroCompetenceStats(): array
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
            'table' => 'PkgCompetences::microCompetence._table',
            default => 'PkgCompetences::microCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('microCompetence_view_type', $default_view_type);
        $microCompetence_viewType = $this->viewState->get('microCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('microCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.microCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.microCompetence.visible");
        }
        
        // Récupération des données
        $microCompetences_data = $this->paginate($params);
        $microCompetences_stats = $this->getmicroCompetenceStats();
       
        $microCompetences_total = collect($microCompetences_stats)->firstWhere('code', 'total')['value'] ?? null;


        $microCompetences_filters = $this->getFieldsFilterable();
        $microCompetence_instance = $this->createInstance();
        $microCompetence_viewTypes = $this->getViewTypes();
        $microCompetence_partialViewName = $this->getPartialViewName($microCompetence_viewType);
        $microCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.microCompetence.stats', $microCompetences_stats);
    
        $microCompetences_permissions = [

            'edit-microCompetence' => Auth::user()->can('edit-microCompetence'),
            'destroy-microCompetence' => Auth::user()->can('destroy-microCompetence'),
            'show-microCompetence' => Auth::user()->can('show-microCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $microCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($microCompetences_data as $item) {
                $microCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'microCompetence_viewTypes',
            'microCompetence_viewType',
            'microCompetences_data',
            'microCompetences_stats',
            'microCompetences_total',
            'microCompetences_filters',
            'microCompetence_instance',
            'microCompetence_title',
            'contextKey',
            'microCompetences_permissions',
            'microCompetences_permissionsByItem'
        );
    
        return [
            'microCompetences_data' => $microCompetences_data,
            'microCompetences_stats' => $microCompetences_stats,
            'microCompetences_stats' => $microCompetences_total,
            'microCompetences_filters' => $microCompetences_filters,
            'microCompetence_instance' => $microCompetence_instance,
            'microCompetence_viewType' => $microCompetence_viewType,
            'microCompetence_viewTypes' => $microCompetence_viewTypes,
            'microCompetence_partialViewName' => $microCompetence_partialViewName,
            'contextKey' => $contextKey,
            'microCompetence_compact_value' => $compact_value,
            'microCompetences_permissions' => $microCompetences_permissions,
            'microCompetences_permissionsByItem' => $microCompetences_permissionsByItem
        ];
    }

}
