<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationMicroCompetenceService pour gérer la persistance de l'entité RealisationMicroCompetence.
 */
class BaseRealisationMicroCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationMicroCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'micro_competence_id',
        'apprenant_id',
        'note_cache',
        'progression_cache',
        'etat_realisation_micro_competence_id',
        'bareme_cache',
        'commentaire_formateur',
        'date_debut',
        'date_fin',
        'dernier_update'
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
     * Constructeur de la classe RealisationMicroCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationMicroCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationMicroCompetence.plural');
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
            $realisationMicroCompetence = $this->find($data['id']);
            $realisationMicroCompetence->fill($data);
        } else {
            $realisationMicroCompetence = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationMicroCompetence->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationMicroCompetence->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationMicroCompetence->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationMicroCompetence->id, $data);
            }
        }

        return $realisationMicroCompetence;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationMicroCompetence');
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
            
            
                if (!array_key_exists('apprenant_id', $scopeVariables)) {


                    $apprenantService = new \Modules\PkgApprenants\Services\ApprenantService();
                    $apprenantIds = $this->getAvailableFilterValues('apprenant_id');
                    $apprenants = $apprenantService->getByIds($apprenantIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::apprenant.plural"), 
                        'apprenant_id', 
                        \Modules\PkgApprenants\Models\Apprenant::class, 
                        'nom',
                        $apprenants
                    );
                }
            
            
                if (!array_key_exists('etat_realisation_micro_competence_id', $scopeVariables)) {


                    $etatRealisationMicroCompetenceService = new \Modules\PkgApprentissage\Services\EtatRealisationMicroCompetenceService();
                    $etatRealisationMicroCompetenceIds = $this->getAvailableFilterValues('etat_realisation_micro_competence_id');
                    $etatRealisationMicroCompetences = $etatRealisationMicroCompetenceService->getByIds($etatRealisationMicroCompetenceIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::etatRealisationMicroCompetence.plural"), 
                        'etat_realisation_micro_competence_id', 
                        \Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence::class, 
                        'nom',
                        $etatRealisationMicroCompetences
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationMicroCompetence.
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
    public function getRealisationMicroCompetenceStats(): array
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
            'table' => 'PkgApprentissage::realisationMicroCompetence._table',
            default => 'PkgApprentissage::realisationMicroCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationMicroCompetence_view_type', $default_view_type);
        $realisationMicroCompetence_viewType = $this->viewState->get('realisationMicroCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationMicroCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationMicroCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationMicroCompetence.visible");
        }
        
        // Récupération des données
        $realisationMicroCompetences_data = $this->paginate($params);
        $realisationMicroCompetences_stats = $this->getrealisationMicroCompetenceStats();
        $realisationMicroCompetences_total = $this->count();
        $realisationMicroCompetences_filters = $this->getFieldsFilterable();
        $realisationMicroCompetence_instance = $this->createInstance();
        $realisationMicroCompetence_viewTypes = $this->getViewTypes();
        $realisationMicroCompetence_partialViewName = $this->getPartialViewName($realisationMicroCompetence_viewType);
        $realisationMicroCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationMicroCompetence.stats', $realisationMicroCompetences_stats);
    
        $realisationMicroCompetences_permissions = [

            'edit-realisationMicroCompetence' => Auth::user()->can('edit-realisationMicroCompetence'),
            'destroy-realisationMicroCompetence' => Auth::user()->can('destroy-realisationMicroCompetence'),
            'show-realisationMicroCompetence' => Auth::user()->can('show-realisationMicroCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationMicroCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationMicroCompetences_data as $item) {
                $realisationMicroCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationMicroCompetence_viewTypes',
            'realisationMicroCompetence_viewType',
            'realisationMicroCompetences_data',
            'realisationMicroCompetences_stats',
            'realisationMicroCompetences_total',
            'realisationMicroCompetences_filters',
            'realisationMicroCompetence_instance',
            'realisationMicroCompetence_title',
            'contextKey',
            'realisationMicroCompetences_permissions',
            'realisationMicroCompetences_permissionsByItem'
        );
    
        return [
            'realisationMicroCompetences_data' => $realisationMicroCompetences_data,
            'realisationMicroCompetences_stats' => $realisationMicroCompetences_stats,
            'realisationMicroCompetences_total' => $realisationMicroCompetences_total,
            'realisationMicroCompetences_filters' => $realisationMicroCompetences_filters,
            'realisationMicroCompetence_instance' => $realisationMicroCompetence_instance,
            'realisationMicroCompetence_viewType' => $realisationMicroCompetence_viewType,
            'realisationMicroCompetence_viewTypes' => $realisationMicroCompetence_viewTypes,
            'realisationMicroCompetence_partialViewName' => $realisationMicroCompetence_partialViewName,
            'contextKey' => $contextKey,
            'realisationMicroCompetence_compact_value' => $compact_value,
            'realisationMicroCompetences_permissions' => $realisationMicroCompetences_permissions,
            'realisationMicroCompetences_permissionsByItem' => $realisationMicroCompetences_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationMicroCompetence_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationMicroCompetence_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationMicroCompetence_ids as $id) {
            $realisationMicroCompetence = $this->find($id);
            $this->authorize('update', $realisationMicroCompetence);
    
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
