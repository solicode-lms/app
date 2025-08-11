<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\App\Manager\JobManager;
use Modules\PkgApprentissage\Models\EtatRealisationCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatRealisationCompetenceService pour gérer la persistance de l'entité EtatRealisationCompetence.
 */
class BaseEtatRealisationCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatRealisationCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'code',
        'nom',
        'description',
        'sys_color_id'
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
     * Constructeur de la classe EtatRealisationCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new EtatRealisationCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprentissage::etatRealisationCompetence.plural');
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
            $etatRealisationCompetence = $this->find($data['id']);
            $etatRealisationCompetence->fill($data);
        } else {
            $etatRealisationCompetence = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($etatRealisationCompetence->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $etatRealisationCompetence->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($etatRealisationCompetence->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($etatRealisationCompetence->id, $data);
            }
        }

        return $etatRealisationCompetence;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('etatRealisationCompetence');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('sys_color_id', $scopeVariables)) {


                    $sysColorService = new \Modules\Core\Services\SysColorService();
                    $sysColorIds = $this->getAvailableFilterValues('sys_color_id');
                    $sysColors = $sysColorService->getByIds($sysColorIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("Core::sysColor.plural"), 
                        'sys_color_id', 
                        \Modules\Core\Models\SysColor::class, 
                        'name',
                        $sysColors
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de etatRealisationCompetence.
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
    public function getEtatRealisationCompetenceStats(): array
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
            'table' => 'PkgApprentissage::etatRealisationCompetence._table',
            default => 'PkgApprentissage::etatRealisationCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatRealisationCompetence_view_type', $default_view_type);
        $etatRealisationCompetence_viewType = $this->viewState->get('etatRealisationCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatRealisationCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.etatRealisationCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.etatRealisationCompetence.visible");
        }
        
        // Récupération des données
        $etatRealisationCompetences_data = $this->paginate($params);
        $etatRealisationCompetences_stats = $this->getetatRealisationCompetenceStats();
        $etatRealisationCompetences_total = $this->count();
        $etatRealisationCompetences_filters = $this->getFieldsFilterable();
        $etatRealisationCompetence_instance = $this->createInstance();
        $etatRealisationCompetence_viewTypes = $this->getViewTypes();
        $etatRealisationCompetence_partialViewName = $this->getPartialViewName($etatRealisationCompetence_viewType);
        $etatRealisationCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatRealisationCompetence.stats', $etatRealisationCompetences_stats);
    
        $etatRealisationCompetences_permissions = [

            'edit-etatRealisationCompetence' => Auth::user()->can('edit-etatRealisationCompetence'),
            'destroy-etatRealisationCompetence' => Auth::user()->can('destroy-etatRealisationCompetence'),
            'show-etatRealisationCompetence' => Auth::user()->can('show-etatRealisationCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatRealisationCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatRealisationCompetences_data as $item) {
                $etatRealisationCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatRealisationCompetence_viewTypes',
            'etatRealisationCompetence_viewType',
            'etatRealisationCompetences_data',
            'etatRealisationCompetences_stats',
            'etatRealisationCompetences_total',
            'etatRealisationCompetences_filters',
            'etatRealisationCompetence_instance',
            'etatRealisationCompetence_title',
            'contextKey',
            'etatRealisationCompetences_permissions',
            'etatRealisationCompetences_permissionsByItem'
        );
    
        return [
            'etatRealisationCompetences_data' => $etatRealisationCompetences_data,
            'etatRealisationCompetences_stats' => $etatRealisationCompetences_stats,
            'etatRealisationCompetences_total' => $etatRealisationCompetences_total,
            'etatRealisationCompetences_filters' => $etatRealisationCompetences_filters,
            'etatRealisationCompetence_instance' => $etatRealisationCompetence_instance,
            'etatRealisationCompetence_viewType' => $etatRealisationCompetence_viewType,
            'etatRealisationCompetence_viewTypes' => $etatRealisationCompetence_viewTypes,
            'etatRealisationCompetence_partialViewName' => $etatRealisationCompetence_partialViewName,
            'contextKey' => $contextKey,
            'etatRealisationCompetence_compact_value' => $compact_value,
            'etatRealisationCompetences_permissions' => $etatRealisationCompetences_permissions,
            'etatRealisationCompetences_permissionsByItem' => $etatRealisationCompetences_permissionsByItem
        ];
    }

    public function bulkUpdateJob($token, $etatRealisationCompetence_ids, $champsCoches, $valeursChamps){
         
       
        $total = count( $etatRealisationCompetence_ids); 
        $jobManager = new JobManager($token,$total);
     

        foreach ($etatRealisationCompetence_ids as $id) {
            $etatRealisationCompetence = $this->find($id);
            $this->authorize('update', $etatRealisationCompetence);
    
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
