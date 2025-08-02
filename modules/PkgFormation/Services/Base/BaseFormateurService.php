<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgFormation\Models\Formateur;
use Modules\Core\Services\BaseService;

/**
 * Classe FormateurService pour gérer la persistance de l'entité Formateur.
 */
class BaseFormateurService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour formateurs.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'matricule',
        'nom',
        'prenom',
        'prenom_arab',
        'nom_arab',
        'email',
        'tele_num',
        'adresse',
        'diplome',
        'echelle',
        'echelon',
        'profile_image',
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
     * Constructeur de la classe FormateurService.
     */
    public function __construct()
    {
        parent::__construct(new Formateur());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::formateur.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('formateur');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('specialites', $scopeVariables)) {

                    $specialiteService = new \Modules\PkgFormation\Services\SpecialiteService();
                    $specialiteIds = $this->getAvailableFilterValues('specialites.id');
                    $specialites = $specialiteService->getByIds($specialiteIds);

                    $this->fieldsFilterable[] = $this->generateManyToManyFilter(
                        __("PkgFormation::specialite.plural"), 
                        'specialite_id', 
                        \Modules\PkgFormation\Models\Specialite::class, 
                        'nom',
                        $specialites
                    );
                }
            
            
                if (!array_key_exists('groupes', $scopeVariables)) {

                    $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                    $groupeIds = $this->getAvailableFilterValues('groupes.id');
                    $groupes = $groupeService->getByIds($groupeIds);

                    $this->fieldsFilterable[] = $this->generateManyToManyFilter(
                        __("PkgApprenants::groupe.plural"), 
                        'groupe_id', 
                        \Modules\PkgApprenants\Models\Groupe::class, 
                        'code',
                        $groupes
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de formateur.
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
    public function getFormateurStats(): array
    {

        $stats = $this->initStats();

        
            $relationStatSpecialite = parent::getStatsByRelation(
                \Modules\PkgFormation\Models\Specialite::class,
                'formateurs',
                'nom'
            );
            $stats = array_merge($stats, $relationStatSpecialite);

        return $stats;
    }


    public function initPassword(int $formateurId)
    {
        $formateur = $this->find($formateurId);
        if (!$formateur) {
            return false; 
        }
        $value =  $formateur->save();
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
            'table' => 'PkgFormation::formateur._table',
            default => 'PkgFormation::formateur._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('formateur_view_type', $default_view_type);
        $formateur_viewType = $this->viewState->get('formateur_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('formateur_view_type') === 'widgets') {
            $this->viewState->set("scope.formateur.visible", 1);
        }else{
            $this->viewState->remove("scope.formateur.visible");
        }
        
        // Récupération des données
        $formateurs_data = $this->paginate($params);
        $formateurs_stats = $this->getformateurStats();
        $formateurs_total = $this->count();
        $formateurs_filters = $this->getFieldsFilterable();
        $formateur_instance = $this->createInstance();
        $formateur_viewTypes = $this->getViewTypes();
        $formateur_partialViewName = $this->getPartialViewName($formateur_viewType);
        $formateur_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.formateur.stats', $formateurs_stats);
    
        $formateurs_permissions = [
            'initPassword-formateur' => Auth::user()->can('initPassword-formateur'),           
            
            'edit-formateur' => Auth::user()->can('edit-formateur'),
            'destroy-formateur' => Auth::user()->can('destroy-formateur'),
            'show-formateur' => Auth::user()->can('show-formateur'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $formateurs_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($formateurs_data as $item) {
                $formateurs_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'formateur_viewTypes',
            'formateur_viewType',
            'formateurs_data',
            'formateurs_stats',
            'formateurs_total',
            'formateurs_filters',
            'formateur_instance',
            'formateur_title',
            'contextKey',
            'formateurs_permissions',
            'formateurs_permissionsByItem'
        );
    
        return [
            'formateurs_data' => $formateurs_data,
            'formateurs_stats' => $formateurs_stats,
            'formateurs_total' => $formateurs_total,
            'formateurs_filters' => $formateurs_filters,
            'formateur_instance' => $formateur_instance,
            'formateur_viewType' => $formateur_viewType,
            'formateur_viewTypes' => $formateur_viewTypes,
            'formateur_partialViewName' => $formateur_partialViewName,
            'contextKey' => $contextKey,
            'formateur_compact_value' => $compact_value,
            'formateurs_permissions' => $formateurs_permissions,
            'formateurs_permissionsByItem' => $formateurs_permissionsByItem
        ];
    }

}
