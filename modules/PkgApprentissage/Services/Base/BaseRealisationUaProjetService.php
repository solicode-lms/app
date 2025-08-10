<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUaProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationUaProjetService pour gérer la persistance de l'entité RealisationUaProjet.
 */
class BaseRealisationUaProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationUaProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'realisation_tache_id',
        'realisation_ua_id',
        'note',
        'bareme',
        'remarque_formateur',
        'date_debut',
        'date_fin'
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
     * Constructeur de la classe RealisationUaProjetService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationUaProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationUaProjet.plural');
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
            $realisationUaProjet = $this->find($data['id']);
            $realisationUaProjet->fill($data);
        } else {
            $realisationUaProjet = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationUaProjet->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationUaProjet->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationUaProjet->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationUaProjet->id, $data);
            }
        }

        return $realisationUaProjet;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationUaProjet');
        $this->fieldsFilterable = [];
        
            
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
            
            
                if (!array_key_exists('realisation_ua_id', $scopeVariables)) {


                    $realisationUaService = new \Modules\PkgApprentissage\Services\RealisationUaService();
                    $realisationUaIds = $this->getAvailableFilterValues('realisation_ua_id');
                    $realisationUas = $realisationUaService->getByIds($realisationUaIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprentissage::realisationUa.plural"), 
                        'realisation_ua_id', 
                        \Modules\PkgApprentissage\Models\RealisationUa::class, 
                        'id',
                        $realisationUas
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationUaProjet.
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
    public function getRealisationUaProjetStats(): array
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
            'table' => 'PkgApprentissage::realisationUaProjet._table',
            default => 'PkgApprentissage::realisationUaProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationUaProjet_view_type', $default_view_type);
        $realisationUaProjet_viewType = $this->viewState->get('realisationUaProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationUaProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationUaProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationUaProjet.visible");
        }
        
        // Récupération des données
        $realisationUaProjets_data = $this->paginate($params);
        $realisationUaProjets_stats = $this->getrealisationUaProjetStats();
        $realisationUaProjets_total = $this->count();
        $realisationUaProjets_filters = $this->getFieldsFilterable();
        $realisationUaProjet_instance = $this->createInstance();
        $realisationUaProjet_viewTypes = $this->getViewTypes();
        $realisationUaProjet_partialViewName = $this->getPartialViewName($realisationUaProjet_viewType);
        $realisationUaProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationUaProjet.stats', $realisationUaProjets_stats);
    
        $realisationUaProjets_permissions = [

            'edit-realisationUaProjet' => Auth::user()->can('edit-realisationUaProjet'),
            'destroy-realisationUaProjet' => Auth::user()->can('destroy-realisationUaProjet'),
            'show-realisationUaProjet' => Auth::user()->can('show-realisationUaProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationUaProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationUaProjets_data as $item) {
                $realisationUaProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationUaProjet_viewTypes',
            'realisationUaProjet_viewType',
            'realisationUaProjets_data',
            'realisationUaProjets_stats',
            'realisationUaProjets_total',
            'realisationUaProjets_filters',
            'realisationUaProjet_instance',
            'realisationUaProjet_title',
            'contextKey',
            'realisationUaProjets_permissions',
            'realisationUaProjets_permissionsByItem'
        );
    
        return [
            'realisationUaProjets_data' => $realisationUaProjets_data,
            'realisationUaProjets_stats' => $realisationUaProjets_stats,
            'realisationUaProjets_total' => $realisationUaProjets_total,
            'realisationUaProjets_filters' => $realisationUaProjets_filters,
            'realisationUaProjet_instance' => $realisationUaProjet_instance,
            'realisationUaProjet_viewType' => $realisationUaProjet_viewType,
            'realisationUaProjet_viewTypes' => $realisationUaProjet_viewTypes,
            'realisationUaProjet_partialViewName' => $realisationUaProjet_partialViewName,
            'contextKey' => $contextKey,
            'realisationUaProjet_compact_value' => $compact_value,
            'realisationUaProjets_permissions' => $realisationUaProjets_permissions,
            'realisationUaProjets_permissionsByItem' => $realisationUaProjets_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationUaProjet_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationUaProjet_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationUaProjet_ids as $id) {
            $realisationUaProjet = $this->find($id);
            $this->authorize('update', $realisationUaProjet);
    
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
