<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgApprenants\Models\ApprenantKonosy;
use Modules\Core\Services\BaseService;

/**
 * Classe ApprenantKonosyService pour gérer la persistance de l'entité ApprenantKonosy.
 */
class BaseApprenantKonosyService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour apprenantKonosies.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'MatriculeEtudiant',
        'Nom',
        'Prenom',
        'Sexe',
        'EtudiantActif',
        'Diplome',
        'Principale',
        'LibelleLong',
        'CodeDiplome',
        'DateNaissance',
        'DateInscription',
        'LieuNaissance',
        'CIN',
        'NTelephone',
        'Adresse',
        'Nationalite',
        'Nom_Arabe',
        'Prenom_Arabe',
        'NiveauScolaire'
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
     * Constructeur de la classe ApprenantKonosyService.
     */
    public function __construct()
    {
        parent::__construct(new ApprenantKonosy());
        $this->fieldsFilterable = [];
        $this->title = __('PkgApprenants::apprenantKonosy.plural');
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
        $scopeVariables = $this->viewState->getScopeVariables('apprenantKonosy');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de apprenantKonosy.
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
    public function getApprenantKonosyStats(): array
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
            'table' => 'PkgApprenants::apprenantKonosy._table',
            default => 'PkgApprenants::apprenantKonosy._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('apprenantKonosy_view_type', $default_view_type);
        $apprenantKonosy_viewType = $this->viewState->get('apprenantKonosy_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('apprenantKonosy_view_type') === 'widgets') {
            $this->viewState->set("scope.apprenantKonosy.visible", 1);
        }else{
            $this->viewState->remove("scope.apprenantKonosy.visible");
        }
        
        // Récupération des données
        $apprenantKonosies_data = $this->paginate($params);
        $apprenantKonosies_stats = $this->getapprenantKonosyStats();
        $apprenantKonosies_total = $this->count();
        $apprenantKonosies_filters = $this->getFieldsFilterable();
        $apprenantKonosy_instance = $this->createInstance();
        $apprenantKonosy_viewTypes = $this->getViewTypes();
        $apprenantKonosy_partialViewName = $this->getPartialViewName($apprenantKonosy_viewType);
        $apprenantKonosy_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.apprenantKonosy.stats', $apprenantKonosies_stats);
    
        $apprenantKonosies_permissions = [

            'edit-apprenantKonosy' => Auth::user()->can('edit-apprenantKonosy'),
            'destroy-apprenantKonosy' => Auth::user()->can('destroy-apprenantKonosy'),
            'show-apprenantKonosy' => Auth::user()->can('show-apprenantKonosy'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $apprenantKonosies_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($apprenantKonosies_data as $item) {
                $apprenantKonosies_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'apprenantKonosy_viewTypes',
            'apprenantKonosy_viewType',
            'apprenantKonosies_data',
            'apprenantKonosies_stats',
            'apprenantKonosies_total',
            'apprenantKonosies_filters',
            'apprenantKonosy_instance',
            'apprenantKonosy_title',
            'contextKey',
            'apprenantKonosies_permissions',
            'apprenantKonosies_permissionsByItem'
        );
    
        return [
            'apprenantKonosies_data' => $apprenantKonosies_data,
            'apprenantKonosies_stats' => $apprenantKonosies_stats,
            'apprenantKonosies_total' => $apprenantKonosies_total,
            'apprenantKonosies_filters' => $apprenantKonosies_filters,
            'apprenantKonosy_instance' => $apprenantKonosy_instance,
            'apprenantKonosy_viewType' => $apprenantKonosy_viewType,
            'apprenantKonosy_viewTypes' => $apprenantKonosy_viewTypes,
            'apprenantKonosy_partialViewName' => $apprenantKonosy_partialViewName,
            'contextKey' => $contextKey,
            'apprenantKonosy_compact_value' => $compact_value,
            'apprenantKonosies_permissions' => $apprenantKonosies_permissions,
            'apprenantKonosies_permissionsByItem' => $apprenantKonosies_permissionsByItem
        ];
    }

}
