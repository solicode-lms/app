<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprentissage\Models\RealisationChapitre;
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
        'chapitre_id',
        'etat_realisation_chapitre_id',
        'date_debut',
        'date_fin',
        'realisation_ua_id',
        'realisation_tache_id',
        'commentaire_formateur'
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
        $this->title = __('PkgApprentissage::realisationChapitre.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationChapitre');
        $this->fieldsFilterable = [];
        
            
                $microCompetenceService = new \Modules\PkgCompetences\Services\MicroCompetenceService();
                $microCompetenceIds = $this->getAvailableFilterValues('Chapitre.UniteApprentissage.Micro_competence_id');
                $microCompetences = $microCompetenceService->getByIds($microCompetenceIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgCompetences::microCompetence.plural"),
                    'Chapitre.UniteApprentissage.Micro_competence_id', 
                    \Modules\PkgCompetences\Models\MicroCompetence::class,
                    "id", 
                    "id",
                    $microCompetences,
                    "[name='chapitre_id']",
                    route('chapitres.getData'),
                    "uniteApprentissage.micro_competence_id"
                    
                );
            
            
                if (!array_key_exists('chapitre_id', $scopeVariables)) {


                    $chapitreService = new \Modules\PkgCompetences\Services\ChapitreService();
                    $chapitreIds = $this->getAvailableFilterValues('chapitre_id');
                    $chapitres = $chapitreService->getByIds($chapitreIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::chapitre.plural"), 
                        'chapitre_id', 
                        \Modules\PkgCompetences\Models\Chapitre::class, 
                        'code',
                        $chapitres
                    );
                }
            
            
                if (!array_key_exists('etat_realisation_chapitre_id', $scopeVariables)) {


                    $etatRealisationChapitreService = new \Modules\PkgApprentissage\Services\EtatRealisationChapitreService();
                    $etatRealisationChapitreIds = $this->getAvailableFilterValues('etat_realisation_chapitre_id');
                    $etatRealisationChapitres = $etatRealisationChapitreService->getByIds($etatRealisationChapitreIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::etatRealisationChapitre.plural"), 
                        'etat_realisation_chapitre_id', 
                        \Modules\PkgApprentissage\Models\EtatRealisationChapitre::class, 
                        'nom',
                        $etatRealisationChapitres
                    );
                }
            
            
                $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                $groupeIds = $this->getAvailableFilterValues('RealisationUa.RealisationMicroCompetence.Apprenant.Groupes.Id');
                $groupes = $groupeService->getByIds($groupeIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgApprenants::groupe.plural"),
                    'RealisationUa.RealisationMicroCompetence.Apprenant.Groupes.Id', 
                    \Modules\PkgApprenants\Models\Groupe::class,
                    "id", 
                    "id",
                    $groupes,
                    "[name='RealisationUa.RealisationMicroCompetence.Apprenant_id']",
                    route('apprenants.getData'),
                    "Groupes.Id"
                    
                );
            
            
                $apprenantService = new \Modules\PkgApprenants\Services\ApprenantService();
                $apprenantIds = $this->getAvailableFilterValues('RealisationUa.RealisationMicroCompetence.Apprenant_id');
                $apprenants = $apprenantService->getByIds($apprenantIds);

                $this->fieldsFilterable[] = $this->generateRelationFilter(
                    __("PkgApprenants::apprenant.plural"),
                    'RealisationUa.RealisationMicroCompetence.Apprenant_id', 
                    \Modules\PkgApprenants\Models\Apprenant::class,
                    "id", 
                    "id",
                    $apprenants
                );
            
            
                if (!array_key_exists('realisation_tache_id', $scopeVariables)) {


                    $realisationTacheService = new \Modules\PkgRealisationTache\Services\RealisationTacheService();
                    $realisationTacheIds = $this->getAvailableFilterValues('realisation_tache_id');
                    $realisationTaches = $realisationTacheService->getByIds($realisationTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::realisationTache.plural"), 
                        'realisation_tache_id', 
                        \Modules\PkgRealisationTache\Models\RealisationTache::class, 
                        'id',
                        $realisationTaches
                    );
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
            'table' => 'PkgApprentissage::realisationChapitre._table',
            default => 'PkgApprentissage::realisationChapitre._table',
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
