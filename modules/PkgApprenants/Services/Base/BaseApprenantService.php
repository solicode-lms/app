<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class BaseApprenantService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenants.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'nom_arab',
        'prenom',
        'prenom_arab',
        'profile_image',
        'cin',
        'date_naissance',
        'sexe',
        'nationalite_id',
        'lieu_naissance',
        'diplome',
        'adresse',
        'niveaux_scolaire_id',
        'tele_num',
        'user_id',
        'matricule',
        'date_inscription',
        'actif'
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
     * Constructeur de la classe ApprenantService.
     */
    public function __construct()
    {
        parent::__construct(new Apprenant());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::apprenant.plural');
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
            $apprenant = $this->find($data['id']);
            $apprenant->fill($data);
        } else {
            $apprenant = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($apprenant->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $apprenant->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($apprenant->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($apprenant->id, $data);
            }
        }

        return $apprenant;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('apprenant');
        $this->fieldsFilterable = [];
        
            
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
            
            
                if (!array_key_exists('actif', $scopeVariables)) {
                    $this->fieldsFilterable[] = [
                        'field' => 'actif', 
                        'type'  => 'Boolean', 
                        'label' => 'actif'
                    ];
                }
            



    }


    /**
     * Crée une nouvelle instance de apprenant.
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
    public function getApprenantStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }


    public function initPassword(int $apprenantId)
    {
        $apprenant = $this->find($apprenantId);
        if (!$apprenant) {
            return false; 
        }
        $value =  $apprenant->save();
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
            'table' => 'PkgApprenants::apprenant._table',
            default => 'PkgApprenants::apprenant._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('apprenant_view_type', $default_view_type);
        $apprenant_viewType = $this->viewState->get('apprenant_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('apprenant_view_type') === 'widgets') {
            $this->viewState->set("scope.apprenant.visible", 1);
        }else{
            $this->viewState->remove("scope.apprenant.visible");
        }
        
        // Récupération des données
        $apprenants_data = $this->paginate($params);
        $apprenants_stats = $this->getapprenantStats();
        $apprenants_total = $this->count();
        $apprenants_filters = $this->getFieldsFilterable();
        $apprenant_instance = $this->createInstance();
        $apprenant_viewTypes = $this->getViewTypes();
        $apprenant_partialViewName = $this->getPartialViewName($apprenant_viewType);
        $apprenant_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.apprenant.stats', $apprenants_stats);
    
        $apprenants_permissions = [
            'initPassword-apprenant' => Auth::user()->can('initPassword-apprenant'),           
            
            'edit-apprenant' => Auth::user()->can('edit-apprenant'),
            'destroy-apprenant' => Auth::user()->can('destroy-apprenant'),
            'show-apprenant' => Auth::user()->can('show-apprenant'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $apprenants_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($apprenants_data as $item) {
                $apprenants_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'apprenant_viewTypes',
            'apprenant_viewType',
            'apprenants_data',
            'apprenants_stats',
            'apprenants_total',
            'apprenants_filters',
            'apprenant_instance',
            'apprenant_title',
            'contextKey',
            'apprenants_permissions',
            'apprenants_permissionsByItem'
        );
    
        return [
            'apprenants_data' => $apprenants_data,
            'apprenants_stats' => $apprenants_stats,
            'apprenants_total' => $apprenants_total,
            'apprenants_filters' => $apprenants_filters,
            'apprenant_instance' => $apprenant_instance,
            'apprenant_viewType' => $apprenant_viewType,
            'apprenant_viewTypes' => $apprenant_viewTypes,
            'apprenant_partialViewName' => $apprenant_partialViewName,
            'contextKey' => $contextKey,
            'apprenant_compact_value' => $compact_value,
            'apprenants_permissions' => $apprenants_permissions,
            'apprenants_permissionsByItem' => $apprenants_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $apprenant_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $apprenant_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($apprenant_ids as $id) {
            $apprenant = $this->find($id);
            $this->authorize('update', $apprenant);
    
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
