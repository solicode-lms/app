<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 */
class BaseRealisationTacheService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationTaches.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'tache_id',
        'realisation_projet_id',
        'dateDebut',
        'dateFin',
        'remarque_evaluateur',
        'etat_realisation_tache_id',
        'note',
        'is_live_coding',
        'remarques_formateur',
        'remarques_apprenant',
        'tache_affectation_id'
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
     * Constructeur de la classe RealisationTacheService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationTache());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationTache::realisationTache.plural');
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
            $realisationTache = $this->find($data['id']);
            $realisationTache->fill($data);
        } else {
            $realisationTache = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($realisationTache->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $realisationTache->hasManyInputsToUpdate = [
                    'evaluationRealisationTaches' => 'evaluationRealisationTache-crud',
                    'realisationChapitres' => 'realisationChapitre-crud',
                    'realisationUaProjets' => 'realisationUaProjet-crud',
                    'realisationUaPrototypes' => 'realisationUaPrototype-crud',
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($realisationTache->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($realisationTache->id, $data);
            }
        }

        return $realisationTache;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('realisationTache');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('tache_id', $scopeVariables)) {


                    $tacheService = new \Modules\PkgCreationTache\Services\TacheService();
                    $tacheIds = $this->getAvailableFilterValues('tache_id');
                    $taches = $tacheService->getByIds($tacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationTache::tache.plural"), 
                        'tache_id', 
                        \Modules\PkgCreationTache\Models\Tache::class, 
                        'titre',
                        $taches
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
            
            
                if (!array_key_exists('etat_realisation_tache_id', $scopeVariables)) {


                    $etatRealisationTacheService = new \Modules\PkgRealisationTache\Services\EtatRealisationTacheService();
                    $etatRealisationTacheIds = $this->getAvailableFilterValues('etat_realisation_tache_id');
                    $etatRealisationTaches = $etatRealisationTacheService->getByIds($etatRealisationTacheIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::etatRealisationTache.plural"), 
                        'etat_realisation_tache_id', 
                        \Modules\PkgRealisationTache\Models\EtatRealisationTache::class, 
                        'nom',
                        $etatRealisationTaches
                    );
                }
            
            
                if (!array_key_exists('tache_affectation_id', $scopeVariables)) {


                    $tacheAffectationService = new \Modules\PkgRealisationTache\Services\TacheAffectationService();
                    $tacheAffectationIds = $this->getAvailableFilterValues('tache_affectation_id');
                    $tacheAffectations = $tacheAffectationService->getByIds($tacheAffectationIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationTache::tacheAffectation.plural"), 
                        'tache_affectation_id', 
                        \Modules\PkgRealisationTache\Models\TacheAffectation::class, 
                        'id',
                        $tacheAffectations
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationTache.
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
    public function getRealisationTacheStats(): array
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
                'icon'  => 'fas fa-table',
            ],
            [
                'type'  => 'table-evaluation',
                'label' => 'Vue évaluation',
                'icon'  => 'fas fa-clipboard-check',
            ],
        ];
    }

    /**
     * Retourne le nom de la vue partielle selon le type de vue sélectionné
     */
    public function getPartialViewName(string $viewType): string
    {
        return match ($viewType) {
            'table' => 'PkgRealisationTache::realisationTache._table',
            'table-evaluation' => 'PkgRealisationTache::realisationTache._table-evaluation',
            default => 'PkgRealisationTache::realisationTache._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationTache_view_type', $default_view_type);
        $realisationTache_viewType = $this->viewState->get('realisationTache_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationTache_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationTache.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationTache.visible");
        }
        
        // Récupération des données
        $realisationTaches_data = $this->paginate($params);
        $realisationTaches_stats = $this->getrealisationTacheStats();
        $realisationTaches_total = $this->count();
        $realisationTaches_filters = $this->getFieldsFilterable();
        $realisationTache_instance = $this->createInstance();
        $realisationTache_viewTypes = $this->getViewTypes();
        $realisationTache_partialViewName = $this->getPartialViewName($realisationTache_viewType);
        $realisationTache_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationTache.stats', $realisationTaches_stats);
    
        $realisationTaches_permissions = [
            'index-livrablesRealisation' => Auth::user()->can('index-livrablesRealisation'),
            'show-projet' => Auth::user()->can('show-projet'),

            'edit-realisationTache' => Auth::user()->can('edit-realisationTache'),
            'destroy-realisationTache' => Auth::user()->can('destroy-realisationTache'),
            'show-realisationTache' => Auth::user()->can('show-realisationTache'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationTaches_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationTaches_data as $item) {
                $realisationTaches_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationTache_viewTypes',
            'realisationTache_viewType',
            'realisationTaches_data',
            'realisationTaches_stats',
            'realisationTaches_total',
            'realisationTaches_filters',
            'realisationTache_instance',
            'realisationTache_title',
            'contextKey',
            'realisationTaches_permissions',
            'realisationTaches_permissionsByItem'
        );
    
        return [
            'realisationTaches_data' => $realisationTaches_data,
            'realisationTaches_stats' => $realisationTaches_stats,
            'realisationTaches_total' => $realisationTaches_total,
            'realisationTaches_filters' => $realisationTaches_filters,
            'realisationTache_instance' => $realisationTache_instance,
            'realisationTache_viewType' => $realisationTache_viewType,
            'realisationTache_viewTypes' => $realisationTache_viewTypes,
            'realisationTache_partialViewName' => $realisationTache_partialViewName,
            'contextKey' => $contextKey,
            'realisationTache_compact_value' => $compact_value,
            'realisationTaches_permissions' => $realisationTaches_permissions,
            'realisationTaches_permissionsByItem' => $realisationTaches_permissionsByItem
        ];
    }

}
