<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Modules\Core\Services\BaseService;

/**
 * Classe EtatsRealisationProjetService pour gérer la persistance de l'entité EtatsRealisationProjet.
 */
class BaseEtatsRealisationProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour etatsRealisationProjets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'titre',
        'code',
        'description',
        'sys_color_id',
        'is_editable_by_formateur'
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
     * Constructeur de la classe EtatsRealisationProjetService.
     */
    public function __construct()
    {
        parent::__construct(new EtatsRealisationProjet());
        $this->fieldsFilterable = [];
        $this->title = __('PkgRealisationProjets::etatsRealisationProjet.plural');
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
        $scopeVariables = $this->viewState->getScopeVariables('etatsRealisationProjet');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de etatsRealisationProjet.
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
    public function getEtatsRealisationProjetStats(): array
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
            'table' => 'PkgRealisationProjets::etatsRealisationProjet._table',
            default => 'PkgRealisationProjets::etatsRealisationProjet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('etatsRealisationProjet_view_type', $default_view_type);
        $etatsRealisationProjet_viewType = $this->viewState->get('etatsRealisationProjet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('etatsRealisationProjet_view_type') === 'widgets') {
            $this->viewState->set("scope.etatsRealisationProjet.visible", 1);
        }else{
            $this->viewState->remove("scope.etatsRealisationProjet.visible");
        }
        
        // Récupération des données
        $etatsRealisationProjets_data = $this->paginate($params);
        $etatsRealisationProjets_stats = $this->getetatsRealisationProjetStats();
        $etatsRealisationProjets_total = $this->count();
        $etatsRealisationProjets_filters = $this->getFieldsFilterable();
        $etatsRealisationProjet_instance = $this->createInstance();
        $etatsRealisationProjet_viewTypes = $this->getViewTypes();
        $etatsRealisationProjet_partialViewName = $this->getPartialViewName($etatsRealisationProjet_viewType);
        $etatsRealisationProjet_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.etatsRealisationProjet.stats', $etatsRealisationProjets_stats);
    
        $etatsRealisationProjets_permissions = [

            'edit-etatsRealisationProjet' => Auth::user()->can('edit-etatsRealisationProjet'),
            'destroy-etatsRealisationProjet' => Auth::user()->can('destroy-etatsRealisationProjet'),
            'show-etatsRealisationProjet' => Auth::user()->can('show-etatsRealisationProjet'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $etatsRealisationProjets_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($etatsRealisationProjets_data as $item) {
                $etatsRealisationProjets_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'etatsRealisationProjet_viewTypes',
            'etatsRealisationProjet_viewType',
            'etatsRealisationProjets_data',
            'etatsRealisationProjets_stats',
            'etatsRealisationProjets_total',
            'etatsRealisationProjets_filters',
            'etatsRealisationProjet_instance',
            'etatsRealisationProjet_title',
            'contextKey',
            'etatsRealisationProjets_permissions',
            'etatsRealisationProjets_permissionsByItem'
        );
    
        return [
            'etatsRealisationProjets_data' => $etatsRealisationProjets_data,
            'etatsRealisationProjets_stats' => $etatsRealisationProjets_stats,
            'etatsRealisationProjets_total' => $etatsRealisationProjets_total,
            'etatsRealisationProjets_filters' => $etatsRealisationProjets_filters,
            'etatsRealisationProjet_instance' => $etatsRealisationProjet_instance,
            'etatsRealisationProjet_viewType' => $etatsRealisationProjet_viewType,
            'etatsRealisationProjet_viewTypes' => $etatsRealisationProjet_viewTypes,
            'etatsRealisationProjet_partialViewName' => $etatsRealisationProjet_partialViewName,
            'contextKey' => $contextKey,
            'etatsRealisationProjet_compact_value' => $compact_value,
            'etatsRealisationProjets_permissions' => $etatsRealisationProjets_permissions,
            'etatsRealisationProjets_permissionsByItem' => $etatsRealisationProjets_permissionsByItem
        ];
    }

}
