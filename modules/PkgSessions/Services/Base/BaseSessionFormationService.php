<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgSessions\Models\SessionFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe SessionFormationService pour gérer la persistance de l'entité SessionFormation.
 */
class BaseSessionFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour sessionFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'titre',
        'thematique',
        'filiere_id',
        'objectifs_pedagogique',
        'titre_prototype',
        'description_prototype',
        'contraintes_prototype',
        'titre_projet',
        'description_projet',
        'contraintes_projet',
        'remarques',
        'date_debut',
        'date_fin',
        'jour_feries_vacances',
        'annee_formation_id'
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
     * Constructeur de la classe SessionFormationService.
     */
    public function __construct()
    {
        parent::__construct(new SessionFormation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgSessions::sessionFormation.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('sessionFormation');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('filiere_id', $scopeVariables)) {


                    $filiereService = new \Modules\PkgFormation\Services\FiliereService();
                    $filiereIds = $this->getAvailableFilterValues('filiere_id');
                    $filieres = $filiereService->getByIds($filiereIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::filiere.plural"), 
                        'filiere_id', 
                        \Modules\PkgFormation\Models\Filiere::class, 
                        'code',
                        $filieres
                    );
                }
            
            
                if (!array_key_exists('annee_formation_id', $scopeVariables)) {


                    $anneeFormationService = new \Modules\PkgFormation\Services\AnneeFormationService();
                    $anneeFormationIds = $this->getAvailableFilterValues('annee_formation_id');
                    $anneeFormations = $anneeFormationService->getByIds($anneeFormationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::anneeFormation.plural"), 
                        'annee_formation_id', 
                        \Modules\PkgFormation\Models\AnneeFormation::class, 
                        'titre',
                        $anneeFormations
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de sessionFormation.
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
    public function getSessionFormationStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function add_projet(int $sessionFormationId)
    {
        $sessionFormation = $this->find($sessionFormationId);
        if (!$sessionFormation) {
            return false; 
        }
        $value =  $sessionFormation->save();
        $this->pushServiceMessage("info","Traitement title", "message : résultat de traitement");
        return $value;
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
            'table' => 'PkgSessions::sessionFormation._table',
            default => 'PkgSessions::sessionFormation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('sessionFormation_view_type', $default_view_type);
        $sessionFormation_viewType = $this->viewState->get('sessionFormation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('sessionFormation_view_type') === 'widgets') {
            $this->viewState->set("scope.sessionFormation.visible", 1);
        }else{
            $this->viewState->remove("scope.sessionFormation.visible");
        }
        
        // Récupération des données
        $sessionFormations_data = $this->paginate($params);
        $sessionFormations_stats = $this->getsessionFormationStats();
        $sessionFormations_filters = $this->getFieldsFilterable();
        $sessionFormation_instance = $this->createInstance();
        $sessionFormation_viewTypes = $this->getViewTypes();
        $sessionFormation_partialViewName = $this->getPartialViewName($sessionFormation_viewType);
        $sessionFormation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.sessionFormation.stats', $sessionFormations_stats);
    
        $sessionFormations_permissions = [
            'add_projet-sessionFormation' => Auth::user()->can('add_projet-sessionFormation'),           
            
            'edit-sessionFormation' => Auth::user()->can('edit-sessionFormation'),
            'destroy-sessionFormation' => Auth::user()->can('destroy-sessionFormation'),
            'show-sessionFormation' => Auth::user()->can('show-sessionFormation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $sessionFormations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($sessionFormations_data as $item) {
                $sessionFormations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'sessionFormation_viewTypes',
            'sessionFormation_viewType',
            'sessionFormations_data',
            'sessionFormations_stats',
            'sessionFormations_filters',
            'sessionFormation_instance',
            'sessionFormation_title',
            'contextKey',
            'sessionFormations_permissions',
            'sessionFormations_permissionsByItem'
        );
    
        return [
            'sessionFormations_data' => $sessionFormations_data,
            'sessionFormations_stats' => $sessionFormations_stats,
            'sessionFormations_filters' => $sessionFormations_filters,
            'sessionFormation_instance' => $sessionFormation_instance,
            'sessionFormation_viewType' => $sessionFormation_viewType,
            'sessionFormation_viewTypes' => $sessionFormation_viewTypes,
            'sessionFormation_partialViewName' => $sessionFormation_partialViewName,
            'contextKey' => $contextKey,
            'sessionFormation_compact_value' => $compact_value,
            'sessionFormations_permissions' => $sessionFormations_permissions,
            'sessionFormations_permissionsByItem' => $sessionFormations_permissionsByItem
        ];
    }

}
