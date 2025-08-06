<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe RealisationProjetService pour gérer la persistance de l'entité RealisationProjet.
 */
class BaseRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour realisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'affectation_projet_id',
        'apprenant_id',
        'date_debut',
        'date_fin',
        'etats_realisation_projet_id',
        'rapport'
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
     * Constructeur de la classe RealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new RealisationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::realisationProjet.plural');
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
        $scopeVariables = $this->viewState->getScopeVariables('realisationProjet');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('affectation_projet_id', $scopeVariables)) {


                    $affectationProjetService = new \Modules\PkgRealisationProjets\Services\AffectationProjetService();
                    $affectationProjetIds = $this->getAvailableFilterValues('affectation_projet_id');
                    $affectationProjets = $affectationProjetService->getByIds($affectationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationProjets::affectationProjet.plural"), 
                        'affectation_projet_id', 
                        \Modules\PkgRealisationProjets\Models\AffectationProjet::class, 
                        'id',
                        $affectationProjets
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
            
            
                if (!array_key_exists('etats_realisation_projet_id', $scopeVariables)) {


                    $etatsRealisationProjetService = new \Modules\PkgRealisationProjets\Services\EtatsRealisationProjetService();
                    $etatsRealisationProjetIds = $this->getAvailableFilterValues('etats_realisation_projet_id');
                    $etatsRealisationProjets = $etatsRealisationProjetService->getByIds($etatsRealisationProjetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgRealisationProjets::etatsRealisationProjet.plural"), 
                        'etats_realisation_projet_id', 
                        \Modules\PkgRealisationProjets\Models\EtatsRealisationProjet::class, 
                        'code',
                        $etatsRealisationProjets
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de realisationProjet.
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
    public function getRealisationProjetStats(): array
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
            'table' => 'PkgRealisationProjets::realisationProjet._table',
            default => 'PkgRealisationProjets::realisationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('realisationProjet_view_type', $default_view_type);
        $realisationProjet_viewType = $this->viewState->get('realisationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('realisationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.realisationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.realisationProjet.visible");
        }
        
        // Récupération des données
        $realisationProjets_data = $this->paginate($params);
        $realisationProjets_stats = $this->getrealisationProjetStats();
        $realisationProjets_total = $this->count();
        $realisationProjets_filters = $this->getFieldsFilterable();
        $realisationProjet_instance = $this->createInstance();
        $realisationProjet_viewTypes = $this->getViewTypes();
        $realisationProjet_partialViewName = $this->getPartialViewName($realisationProjet_viewType);
        $realisationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.realisationProjet.stats', $realisationProjets_stats);
    
        $realisationProjets_permissions = [

            'edit-realisationProjet' => Auth::user()->can('edit-realisationProjet'),
            'destroy-realisationProjet' => Auth::user()->can('destroy-realisationProjet'),
            'show-realisationProjet' => Auth::user()->can('show-realisationProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $realisationProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($realisationProjets_data as $item) {
                $realisationProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'realisationProjet_viewTypes',
            'realisationProjet_viewType',
            'realisationProjets_data',
            'realisationProjets_stats',
            'realisationProjets_total',
            'realisationProjets_filters',
            'realisationProjet_instance',
            'realisationProjet_title',
            'contextKey',
            'realisationProjets_permissions',
            'realisationProjets_permissionsByItem'
        );
    
        return [
            'realisationProjets_data' => $realisationProjets_data,
            'realisationProjets_stats' => $realisationProjets_stats,
            'realisationProjets_total' => $realisationProjets_total,
            'realisationProjets_filters' => $realisationProjets_filters,
            'realisationProjet_instance' => $realisationProjet_instance,
            'realisationProjet_viewType' => $realisationProjet_viewType,
            'realisationProjet_viewTypes' => $realisationProjet_viewTypes,
            'realisationProjet_partialViewName' => $realisationProjet_partialViewName,
            'contextKey' => $contextKey,
            'realisationProjet_compact_value' => $compact_value,
            'realisationProjets_permissions' => $realisationProjets_permissions,
            'realisationProjets_permissionsByItem' => $realisationProjets_permissionsByItem
        ];
    }

}
