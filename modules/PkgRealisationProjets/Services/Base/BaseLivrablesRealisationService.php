<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Modules\Core\Services\BaseService;

/**
 * Classe LivrablesRealisationService pour gérer la persistance de l'entité LivrablesRealisation.
 */
class BaseLivrablesRealisationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour livrablesRealisations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'livrable_id',
        'lien',
        'titre',
        'description',
        'realisation_projet_id'
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
     * Constructeur de la classe LivrablesRealisationService.
     */
    public function __construct()
    {
        parent::__construct(new LivrablesRealisation());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::livrablesRealisation.plural');
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
            $livrablesRealisation = $this->find($data['id']);
            $livrablesRealisation->fill($data);
        } else {
            $livrablesRealisation = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($livrablesRealisation->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $livrablesRealisation->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($livrablesRealisation->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($livrablesRealisation->id, $data);
            }
        }

        return $livrablesRealisation;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('livrablesRealisation');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('livrable_id', $scopeVariables)) {


                    $livrableService = new \Modules\PkgCreationProjet\Services\LivrableService();
                    $livrableIds = $this->getAvailableFilterValues('livrable_id');
                    $livrables = $livrableService->getByIds($livrableIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::livrable.plural"), 
                        'livrable_id', 
                        \Modules\PkgCreationProjet\Models\Livrable::class, 
                        'titre',
                        $livrables
                    );
                }
            
            
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
            



    }


    /**
     * Crée une nouvelle instance de livrablesRealisation.
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
    public function getLivrablesRealisationStats(): array
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
            'table' => 'PkgRealisationProjets::livrablesRealisation._table',
            default => 'PkgRealisationProjets::livrablesRealisation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('livrablesRealisation_view_type', $default_view_type);
        $livrablesRealisation_viewType = $this->viewState->get('livrablesRealisation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('livrablesRealisation_view_type') === 'widgets') {
            $this->viewState->set("scope.livrablesRealisation.visible", 1);
        }else{
            $this->viewState->remove("scope.livrablesRealisation.visible");
        }
        
        // Récupération des données
        $livrablesRealisations_data = $this->paginate($params);
        $livrablesRealisations_stats = $this->getlivrablesRealisationStats();
        $livrablesRealisations_total = $this->count();
        $livrablesRealisations_filters = $this->getFieldsFilterable();
        $livrablesRealisation_instance = $this->createInstance();
        $livrablesRealisation_viewTypes = $this->getViewTypes();
        $livrablesRealisation_partialViewName = $this->getPartialViewName($livrablesRealisation_viewType);
        $livrablesRealisation_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.livrablesRealisation.stats', $livrablesRealisations_stats);
    
        $livrablesRealisations_permissions = [

            'edit-livrablesRealisation' => Auth::user()->can('edit-livrablesRealisation'),
            'destroy-livrablesRealisation' => Auth::user()->can('destroy-livrablesRealisation'),
            'show-livrablesRealisation' => Auth::user()->can('show-livrablesRealisation'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $livrablesRealisations_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($livrablesRealisations_data as $item) {
                $livrablesRealisations_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'livrablesRealisation_viewTypes',
            'livrablesRealisation_viewType',
            'livrablesRealisations_data',
            'livrablesRealisations_stats',
            'livrablesRealisations_total',
            'livrablesRealisations_filters',
            'livrablesRealisation_instance',
            'livrablesRealisation_title',
            'contextKey',
            'livrablesRealisations_permissions',
            'livrablesRealisations_permissionsByItem'
        );
    
        return [
            'livrablesRealisations_data' => $livrablesRealisations_data,
            'livrablesRealisations_stats' => $livrablesRealisations_stats,
            'livrablesRealisations_total' => $livrablesRealisations_total,
            'livrablesRealisations_filters' => $livrablesRealisations_filters,
            'livrablesRealisation_instance' => $livrablesRealisation_instance,
            'livrablesRealisation_viewType' => $livrablesRealisation_viewType,
            'livrablesRealisation_viewTypes' => $livrablesRealisation_viewTypes,
            'livrablesRealisation_partialViewName' => $livrablesRealisation_partialViewName,
            'contextKey' => $contextKey,
            'livrablesRealisation_compact_value' => $compact_value,
            'livrablesRealisations_permissions' => $livrablesRealisations_permissions,
            'livrablesRealisations_permissionsByItem' => $livrablesRealisations_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $livrablesRealisation_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $livrablesRealisation_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($livrablesRealisation_ids as $id) {
            $livrablesRealisation = $this->find($id);
            $this->authorize('update', $livrablesRealisation);
    
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
