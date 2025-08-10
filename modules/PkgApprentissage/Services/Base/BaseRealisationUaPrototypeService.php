<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationUaPrototypeService pour gérer la persistance de l'entité RealisationUaPrototype.
 */
class BaseRealisationUaPrototypeService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationUaPrototypes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'realisation_tache_id',
        'realisation_ua_id',
        'bareme',
        'note',
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
     * Constructeur de la classe RealisationUaPrototypeService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationUaPrototype());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::realisationUaPrototype.plural');
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
            $realisationUaPrototype = $this->find($data['id']);
            $realisationUaPrototype->fill($data);
        } else {
            $realisationUaPrototype = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationUaPrototype->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationUaPrototype->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationUaPrototype->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationUaPrototype->id, $data);
            }
        }

        return $realisationUaPrototype;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationUaPrototype');
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
     * Crée une nouvelle instance de realisationUaPrototype.
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
    public function getRealisationUaPrototypeStats(): array
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
            'table' => 'PkgApprentissage::realisationUaPrototype._table',
            default => 'PkgApprentissage::realisationUaPrototype._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationUaPrototype_view_type', $default_view_type);
        $realisationUaPrototype_viewType = $this->viewState->get('realisationUaPrototype_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationUaPrototype_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationUaPrototype.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationUaPrototype.visible");
        }
        
        // Récupération des données
        $realisationUaPrototypes_data = $this->paginate($params);
        $realisationUaPrototypes_stats = $this->getrealisationUaPrototypeStats();
        $realisationUaPrototypes_total = $this->count();
        $realisationUaPrototypes_filters = $this->getFieldsFilterable();
        $realisationUaPrototype_instance = $this->createInstance();
        $realisationUaPrototype_viewTypes = $this->getViewTypes();
        $realisationUaPrototype_partialViewName = $this->getPartialViewName($realisationUaPrototype_viewType);
        $realisationUaPrototype_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationUaPrototype.stats', $realisationUaPrototypes_stats);
    
        $realisationUaPrototypes_permissions = [

            'edit-realisationUaPrototype' => Auth::user()->can('edit-realisationUaPrototype'),
            'destroy-realisationUaPrototype' => Auth::user()->can('destroy-realisationUaPrototype'),
            'show-realisationUaPrototype' => Auth::user()->can('show-realisationUaPrototype'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationUaPrototypes_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationUaPrototypes_data as $item) {
                $realisationUaPrototypes_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationUaPrototype_viewTypes',
            'realisationUaPrototype_viewType',
            'realisationUaPrototypes_data',
            'realisationUaPrototypes_stats',
            'realisationUaPrototypes_total',
            'realisationUaPrototypes_filters',
            'realisationUaPrototype_instance',
            'realisationUaPrototype_title',
            'contextKey',
            'realisationUaPrototypes_permissions',
            'realisationUaPrototypes_permissionsByItem'
        );
    
        return [
            'realisationUaPrototypes_data' => $realisationUaPrototypes_data,
            'realisationUaPrototypes_stats' => $realisationUaPrototypes_stats,
            'realisationUaPrototypes_total' => $realisationUaPrototypes_total,
            'realisationUaPrototypes_filters' => $realisationUaPrototypes_filters,
            'realisationUaPrototype_instance' => $realisationUaPrototype_instance,
            'realisationUaPrototype_viewType' => $realisationUaPrototype_viewType,
            'realisationUaPrototype_viewTypes' => $realisationUaPrototype_viewTypes,
            'realisationUaPrototype_partialViewName' => $realisationUaPrototype_partialViewName,
            'contextKey' => $contextKey,
            'realisationUaPrototype_compact_value' => $compact_value,
            'realisationUaPrototypes_permissions' => $realisationUaPrototypes_permissions,
            'realisationUaPrototypes_permissionsByItem' => $realisationUaPrototypes_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $realisationUaPrototype_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $realisationUaPrototype_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($realisationUaPrototype_ids as $id) {
            $realisationUaPrototype = $this->find($id);
            $this->authorize('update', $realisationUaPrototype);
    
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
