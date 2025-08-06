<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe AffectationProjetService pour gérer la persistance de l'entité AffectationProjet.
 */
class BaseAffectationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour affectationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'projet_id',
        'groupe_id',
        'sous_groupe_id',
        'annee_formation_id',
        'date_debut',
        'date_fin',
        'is_formateur_evaluateur',
        'echelle_note_cible',
        'description'
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
     * Constructeur de la classe AffectationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new AffectationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::affectationProjet.plural');
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
        $scopeVariables = $this->viewState->getScopeVariables('affectationProjet');
        $this->fieldsFilterable = [];
        
            
                if (!array_key_exists('projet_id', $scopeVariables)) {


                    $projetService = new \Modules\PkgCreationProjet\Services\ProjetService();
                    $projetIds = $this->getAvailableFilterValues('projet_id');
                    $projets = $projetService->getByIds($projetIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgCreationProjet::projet.plural"), 
                        'projet_id', 
                        \Modules\PkgCreationProjet\Models\Projet::class, 
                        'titre',
                        $projets
                    );
                }
            
            
                if (!array_key_exists('groupe_id', $scopeVariables)) {


                    $groupeService = new \Modules\PkgApprenants\Services\GroupeService();
                    $groupeIds = $this->getAvailableFilterValues('groupe_id');
                    $groupes = $groupeService->getByIds($groupeIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::groupe.plural"), 
                        'groupe_id', 
                        \Modules\PkgApprenants\Models\Groupe::class, 
                        'code',
                        $groupes
                    );
                }
            
            
                if (!array_key_exists('sous_groupe_id', $scopeVariables)) {


                    $sousGroupeService = new \Modules\PkgApprenants\Services\SousGroupeService();
                    $sousGroupeIds = $this->getAvailableFilterValues('sous_groupe_id');
                    $sousGroupes = $sousGroupeService->getByIds($sousGroupeIds);

                    $this->fieldsFilterable[] = $this->generateManyToOneFilter(
                        __("PkgApprenants::sousGroupe.plural"), 
                        'sous_groupe_id', 
                        \Modules\PkgApprenants\Models\SousGroupe::class, 
                        'nom',
                        $sousGroupes
                    );
                }
            



    }


    /**
     * Crée une nouvelle instance de affectationProjet.
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
    public function getAffectationProjetStats(): array
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

    public function exportPV(int $affectationProjetId)
    {
        $affectationProjet = $this->find($affectationProjetId);
        if (!$affectationProjet) {
            return false; 
        }
        $value =  $affectationProjet->save();
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
            'table' => 'PkgRealisationProjets::affectationProjet._table',
            default => 'PkgRealisationProjets::affectationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('affectationProjet_view_type', $default_view_type);
        $affectationProjet_viewType = $this->viewState->get('affectationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('affectationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.affectationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.affectationProjet.visible");
        }
        
        // Récupération des données
        $affectationProjets_data = $this->paginate($params);
        $affectationProjets_stats = $this->getaffectationProjetStats();
        $affectationProjets_total = $this->count();
        $affectationProjets_filters = $this->getFieldsFilterable();
        $affectationProjet_instance = $this->createInstance();
        $affectationProjet_viewTypes = $this->getViewTypes();
        $affectationProjet_partialViewName = $this->getPartialViewName($affectationProjet_viewType);
        $affectationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.affectationProjet.stats', $affectationProjets_stats);
    
        $affectationProjets_permissions = [
            'exportPV-affectationProjet' => Auth::user()->can('exportPV-affectationProjet'),           
            
            'edit-affectationProjet' => Auth::user()->can('edit-affectationProjet'),
            'destroy-affectationProjet' => Auth::user()->can('destroy-affectationProjet'),
            'show-affectationProjet' => Auth::user()->can('show-affectationProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $affectationProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($affectationProjets_data as $item) {
                $affectationProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'affectationProjet_viewTypes',
            'affectationProjet_viewType',
            'affectationProjets_data',
            'affectationProjets_stats',
            'affectationProjets_total',
            'affectationProjets_filters',
            'affectationProjet_instance',
            'affectationProjet_title',
            'contextKey',
            'affectationProjets_permissions',
            'affectationProjets_permissionsByItem'
        );
    
        return [
            'affectationProjets_data' => $affectationProjets_data,
            'affectationProjets_stats' => $affectationProjets_stats,
            'affectationProjets_total' => $affectationProjets_total,
            'affectationProjets_filters' => $affectationProjets_filters,
            'affectationProjet_instance' => $affectationProjet_instance,
            'affectationProjet_viewType' => $affectationProjet_viewType,
            'affectationProjet_viewTypes' => $affectationProjet_viewTypes,
            'affectationProjet_partialViewName' => $affectationProjet_partialViewName,
            'contextKey' => $contextKey,
            'affectationProjet_compact_value' => $compact_value,
            'affectationProjets_permissions' => $affectationProjets_permissions,
            'affectationProjets_permissionsByItem' => $affectationProjets_permissionsByItem
        ];
    }

}
