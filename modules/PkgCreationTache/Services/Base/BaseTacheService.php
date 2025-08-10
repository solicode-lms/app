<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationTache\Models\Tache;
use Modules\Core\Services\BaseService;

/**
 * Classe TacheService pour gérer la persistance de l'entité Tache.
 */
class BaseTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour taches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'priorite',
        'titre',
        'projet_id',
        'description',
        'dateDebut',
        'dateFin',
        'note',
        'phase_evaluation_id',
        'chapitre_id',
        'priorite_tache_id'
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
     * Constructeur de la classe TacheService.
     */
    public function __construct()
    {
        parent::__construct(new Tache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationTache::tache.plural');
    }


    /**
     * Applique les calculs dynamiques sur les champs marqués avec l’attribut `data-calcule`
     * pendant l’édition ou la création d’une entité.
     *
     * Cette méthode est utilisée dans les formulaires dynamiques pour recalculer certains champs
     * (ex : note, barème, état, progression...) en fonction des valeurs saisies ou modifiées.
     *
     * Elle est déclenchée automatiquement lorsqu’un champ du formulaire possède l’attribut `data-calcule`.
     *
     * @param mixed $data Données en cours d’édition (array ou modèle hydraté sans persistance).
     * @return mixed L’entité enrichie avec les champs recalculés.
     */
    public function dataCalcul($data)
    {
        // 🧾 Chargement ou initialisation de l'entité
        if (!empty($data['id'])) {
            $tache = $this->find($data['id']);
            $tache->fill($data);
        } else {
            $tache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($tache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $tache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($tache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($tache->id, $data);
            }
        }

        return $tache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('tache');
        $this->fieldsFilterable = [];
        
            
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
            
            
                if (!array_key_exists('phase_evaluation_id', $scopeVariables)) {


                    $phaseEvaluationService = new \Modules\PkgCompetences\Services\PhaseEvaluationService();
                    $phaseEvaluationIds = $this->getAvailableFilterValues('phase_evaluation_id');
                    $phaseEvaluations = $phaseEvaluationService->getByIds($phaseEvaluationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCompetences::phaseEvaluation.plural"), 
                        'phase_evaluation_id', 
                        \Modules\PkgCompetences\Models\PhaseEvaluation::class, 
                        'code',
                        $phaseEvaluations
                    );
                }
            
            
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
            
            
                if (!array_key_exists('priorite_tache_id', $scopeVariables)) {


                    $prioriteTacheService = new \Modules\PkgCreationTache\Services\PrioriteTacheService();
                    $prioriteTacheIds = $this->getAvailableFilterValues('priorite_tache_id');
                    $prioriteTaches = $prioriteTacheService->getByIds($prioriteTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationTache::prioriteTache.plural"), 
                        'priorite_tache_id', 
                        \Modules\PkgCreationTache\Models\PrioriteTache::class, 
                        'nom',
                        $prioriteTaches
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de tache.
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
    public function getTacheStats(): array
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
            'table' => 'PkgCreationTache::tache._table',
            default => 'PkgCreationTache::tache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('tache_view_type', $default_view_type);
        $tache_viewType = $this->viewState->get('tache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('tache_view_type') === 'widgets') {
            $this->viewState->set("scope.tache.visible", 1);
        }else{
            $this->viewState->remove("scope.tache.visible");
        }
        
        // Récupération des données
        $taches_data = $this->paginate($params);
        $taches_stats = $this->gettacheStats();
        $taches_total = $this->count();
        $taches_filters = $this->getFieldsFilterable();
        $tache_instance = $this->createInstance();
        $tache_viewTypes = $this->getViewTypes();
        $tache_partialViewName = $this->getPartialViewName($tache_viewType);
        $tache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.tache.stats', $taches_stats);
    
        $taches_permissions = [

            'edit-tache' => Auth::user()->can('edit-tache'),
            'destroy-tache' => Auth::user()->can('destroy-tache'),
            'show-tache' => Auth::user()->can('show-tache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $taches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($taches_data as $item) {
                $taches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'tache_viewTypes',
            'tache_viewType',
            'taches_data',
            'taches_stats',
            'taches_total',
            'taches_filters',
            'tache_instance',
            'tache_title',
            'contextKey',
            'taches_permissions',
            'taches_permissionsByItem'
        );
    
        return [
            'taches_data' => $taches_data,
            'taches_stats' => $taches_stats,
            'taches_total' => $taches_total,
            'taches_filters' => $taches_filters,
            'tache_instance' => $tache_instance,
            'tache_viewType' => $tache_viewType,
            'tache_viewTypes' => $tache_viewTypes,
            'tache_partialViewName' => $tache_partialViewName,
            'contextKey' => $contextKey,
            'tache_compact_value' => $compact_value,
            'taches_permissions' => $taches_permissions,
            'taches_permissionsByItem' => $taches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $tache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $tache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($tache_ids as $id) {
            $tache = $this->find($id);
            $this->authorize('update', $tache);
    
            $allFields = $this->getFieldsEditable();
            $data = collect($allFields)
                ->filter(fn($field) => in_array($field, $champsCoches))
                ->mapWithKeys(fn($field) => [$field => $valeursChamps[$field]])
                ->toArray();
    
            if (!empty($data)) {
                $this->updateOnlyExistanteAttribute($id, $data);
            }

            $jobManager->tick();
            
        }

        return "done";
    }

}
