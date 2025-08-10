<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgCreationTache\Models\PrioriteTache;
use Modules\Core\Services\BaseService;

/**
 * Classe PrioriteTacheService pour gérer la persistance de l'entité PrioriteTache.
 */
class BasePrioriteTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour prioriteTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'description',
        'formateur_id'
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
     * Constructeur de la classe PrioriteTacheService.
     */
    public function __construct()
    {
        parent::__construct(new PrioriteTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationTache::prioriteTache.plural');
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
            $prioriteTache = $this->find($data['id']);
            $prioriteTache->fill($data);
        } else {
            $prioriteTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($prioriteTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $prioriteTache->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($prioriteTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($prioriteTache->id, $data);
            }
        }

        return $prioriteTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('prioriteTache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('formateur_id', $scopeVariables)) {


                    $formateurService = new \Modules\PkgFormation\Services\FormateurService();
                    $formateurIds = $this->getAvailableFilterValues('formateur_id');
                    $formateurs = $formateurService->getByIds($formateurIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgFormation::formateur.plural"), 
                        'formateur_id', 
                        \Modules\PkgFormation\Models\Formateur::class, 
                        'nom',
                        $formateurs
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de prioriteTache.
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
    public function getPrioriteTacheStats(): array
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
            'table' => 'PkgCreationTache::prioriteTache._table',
            default => 'PkgCreationTache::prioriteTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('prioriteTache_view_type', $default_view_type);
        $prioriteTache_viewType = $this->viewState->get('prioriteTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('prioriteTache_view_type') === 'widgets') {
            $this->viewState->set("scope.prioriteTache.visible", 1);
        }else{
            $this->viewState->remove("scope.prioriteTache.visible");
        }
        
        // Récupération des données
        $prioriteTaches_data = $this->paginate($params);
        $prioriteTaches_stats = $this->getprioriteTacheStats();
        $prioriteTaches_total = $this->count();
        $prioriteTaches_filters = $this->getFieldsFilterable();
        $prioriteTache_instance = $this->createInstance();
        $prioriteTache_viewTypes = $this->getViewTypes();
        $prioriteTache_partialViewName = $this->getPartialViewName($prioriteTache_viewType);
        $prioriteTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.prioriteTache.stats', $prioriteTaches_stats);
    
        $prioriteTaches_permissions = [

            'edit-prioriteTache' => Auth::user()->can('edit-prioriteTache'),
            'destroy-prioriteTache' => Auth::user()->can('destroy-prioriteTache'),
            'show-prioriteTache' => Auth::user()->can('show-prioriteTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $prioriteTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($prioriteTaches_data as $item) {
                $prioriteTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'prioriteTache_viewTypes',
            'prioriteTache_viewType',
            'prioriteTaches_data',
            'prioriteTaches_stats',
            'prioriteTaches_total',
            'prioriteTaches_filters',
            'prioriteTache_instance',
            'prioriteTache_title',
            'contextKey',
            'prioriteTaches_permissions',
            'prioriteTaches_permissionsByItem'
        );
    
        return [
            'prioriteTaches_data' => $prioriteTaches_data,
            'prioriteTaches_stats' => $prioriteTaches_stats,
            'prioriteTaches_total' => $prioriteTaches_total,
            'prioriteTaches_filters' => $prioriteTaches_filters,
            'prioriteTache_instance' => $prioriteTache_instance,
            'prioriteTache_viewType' => $prioriteTache_viewType,
            'prioriteTache_viewTypes' => $prioriteTache_viewTypes,
            'prioriteTache_partialViewName' => $prioriteTache_partialViewName,
            'contextKey' => $contextKey,
            'prioriteTache_compact_value' => $compact_value,
            'prioriteTaches_permissions' => $prioriteTaches_permissions,
            'prioriteTaches_permissionsByItem' => $prioriteTaches_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $prioriteTache_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $prioriteTache_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($prioriteTache_ids as $id) {
            $prioriteTache = $this->find($id);
            $this->authorize('update', $prioriteTache);
    
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
