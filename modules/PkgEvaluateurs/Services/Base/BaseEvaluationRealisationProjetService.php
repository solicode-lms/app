<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe EvaluationRealisationProjetService pour gérer la persistance de l'entité EvaluationRealisationProjet.
 */
class BaseEvaluationRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour evaluationRealisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'realisation_projet_id',
        'evaluateur_id',
        'date_evaluation',
        'etat_evaluation_projet_id',
        'remarques'
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
     * Constructeur de la classe EvaluationRealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EvaluationRealisationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgEvaluateurs::evaluationRealisationProjet.plural');
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
            $evaluationRealisationProjet = $this->find($data['id']);
            $evaluationRealisationProjet->fill($data);
        } else {
            $evaluationRealisationProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($evaluationRealisationProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $evaluationRealisationProjet->hasManyInputsToUpdate = [
                    'evaluationRealisationTaches' => 'evaluationRealisationTache-crud',
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($evaluationRealisationProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($evaluationRealisationProjet->id, $data);
            }
        }

        return $evaluationRealisationProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('evaluationRealisationProjet');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('realisation_projet_id', $scopeVariables)) {


                    $realisationProjetService = new \Modules\PkgRealisationProjets\Services\RealisationProjetService();
                    $realisationProjetIds = $this->getAvailableFilterValues('realisation_projet_id');
                    $realisationProjets = $realisationProjetService->getByIds($realisationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationProjets::realisationProjet.plural"), 
                        'realisation_projet_id', 
                        \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 
                        'id',
                        $realisationProjets
                    );
                }
            
            
                if (!array_key_exists('evaluateur_id', $scopeVariables)) {


                    $evaluateurService = new \Modules\PkgEvaluateurs\Services\EvaluateurService();
                    $evaluateurIds = $this->getAvailableFilterValues('evaluateur_id');
                    $evaluateurs = $evaluateurService->getByIds($evaluateurIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgEvaluateurs::evaluateur.plural"), 
                        'evaluateur_id', 
                        \Modules\PkgEvaluateurs\Models\Evaluateur::class, 
                        'nom',
                        $evaluateurs
                    );
                }
            
            
                if (!array_key_exists('etat_evaluation_projet_id', $scopeVariables)) {


                    $etatEvaluationProjetService = new \Modules\PkgEvaluateurs\Services\EtatEvaluationProjetService();
                    $etatEvaluationProjetIds = $this->getAvailableFilterValues('etat_evaluation_projet_id');
                    $etatEvaluationProjets = $etatEvaluationProjetService->getByIds($etatEvaluationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgEvaluateurs::etatEvaluationProjet.plural"), 
                        'etat_evaluation_projet_id', 
                        \Modules\PkgEvaluateurs\Models\EtatEvaluationProjet::class, 
                        'code',
                        $etatEvaluationProjets
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de evaluationRealisationProjet.
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
    public function getEvaluationRealisationProjetStats(): array
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
            'table' => 'PkgEvaluateurs::evaluationRealisationProjet._table',
            default => 'PkgEvaluateurs::evaluationRealisationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('evaluationRealisationProjet_view_type', $default_view_type);
        $evaluationRealisationProjet_viewType = $this->viewState->get('evaluationRealisationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('evaluationRealisationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.evaluationRealisationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.evaluationRealisationProjet.visible");
        }
        
        // Récupération des données
        $evaluationRealisationProjets_data = $this->paginate($params);
        $evaluationRealisationProjets_stats = $this->getevaluationRealisationProjetStats();
        $evaluationRealisationProjets_total = $this->count();
        $evaluationRealisationProjets_filters = $this->getFieldsFilterable();
        $evaluationRealisationProjet_instance = $this->createInstance();
        $evaluationRealisationProjet_viewTypes = $this->getViewTypes();
        $evaluationRealisationProjet_partialViewName = $this->getPartialViewName($evaluationRealisationProjet_viewType);
        $evaluationRealisationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.evaluationRealisationProjet.stats', $evaluationRealisationProjets_stats);
    
        $evaluationRealisationProjets_permissions = [

            'edit-evaluationRealisationProjet' => Auth::user()->can('edit-evaluationRealisationProjet'),
            'destroy-evaluationRealisationProjet' => Auth::user()->can('destroy-evaluationRealisationProjet'),
            'show-evaluationRealisationProjet' => Auth::user()->can('show-evaluationRealisationProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $evaluationRealisationProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($evaluationRealisationProjets_data as $item) {
                $evaluationRealisationProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'evaluationRealisationProjet_viewTypes',
            'evaluationRealisationProjet_viewType',
            'evaluationRealisationProjets_data',
            'evaluationRealisationProjets_stats',
            'evaluationRealisationProjets_total',
            'evaluationRealisationProjets_filters',
            'evaluationRealisationProjet_instance',
            'evaluationRealisationProjet_title',
            'contextKey',
            'evaluationRealisationProjets_permissions',
            'evaluationRealisationProjets_permissionsByItem'
        );
    
        return [
            'evaluationRealisationProjets_data' => $evaluationRealisationProjets_data,
            'evaluationRealisationProjets_stats' => $evaluationRealisationProjets_stats,
            'evaluationRealisationProjets_total' => $evaluationRealisationProjets_total,
            'evaluationRealisationProjets_filters' => $evaluationRealisationProjets_filters,
            'evaluationRealisationProjet_instance' => $evaluationRealisationProjet_instance,
            'evaluationRealisationProjet_viewType' => $evaluationRealisationProjet_viewType,
            'evaluationRealisationProjet_viewTypes' => $evaluationRealisationProjet_viewTypes,
            'evaluationRealisationProjet_partialViewName' => $evaluationRealisationProjet_partialViewName,
            'contextKey' => $contextKey,
            'evaluationRealisationProjet_compact_value' => $compact_value,
            'evaluationRealisationProjets_permissions' => $evaluationRealisationProjets_permissions,
            'evaluationRealisationProjets_permissionsByItem' => $evaluationRealisationProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $evaluationRealisationProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $evaluationRealisationProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($evaluationRealisationProjet_ids as $id) {
            $evaluationRealisationProjet = $this->find($id);
            $this->authorize('update', $evaluationRealisationProjet);
    
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
