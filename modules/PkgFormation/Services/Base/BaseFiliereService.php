<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgFormation\Models\Filiere;
use Modules\Core\Services\BaseService;

/**
 * Classe FiliereService pour gérer la persistance de l'entité Filiere.
 */
class BaseFiliereService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour filieres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'code',
        'nom',
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
     * Constructeur de la classe FiliereService.
     */
    public function __construct()
    {
        parent::__construct(new Filiere());
        $this->fieldsFilterable = [];
        $this->title = __('PkgFormation::filiere.plural');
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
            $filiere = $this->find($data['id']);
            $filiere->fill($data);
        } else {
            $filiere = $this->createInstance($data);
        }

        // 🛠️ Traitement spécifique en mode édition
        if (!empty($filiere->id)) {
            // 🔄 Déclaration des composants hasMany à mettre à jour
            $filiere->hasManyInputsToUpdate = [
            ];

            // 💡 Mise à jour temporaire des attributs pour affichage (sans sauvegarde en base)
            if (!empty($filiere->hasManyInputsToUpdate)) {
                $this->updateOnlyExistanteAttribute($filiere->id, $data);
            }
        }

        return $filiere;
    }

    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('filiere');
        $this->fieldsFilterable = [];
        



    }


    /**
     * Crée une nouvelle instance de filiere.
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
    public function getFiliereStats(): array
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
            'table' => 'PkgFormation::filiere._table',
            default => 'PkgFormation::filiere._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('filiere_view_type', $default_view_type);
        $filiere_viewType = $this->viewState->get('filiere_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('filiere_view_type') === 'widgets') {
            $this->viewState->set("scope.filiere.visible", 1);
        }else{
            $this->viewState->remove("scope.filiere.visible");
        }
        
        // Récupération des données
        $filieres_data = $this->paginate($params);
        $filieres_stats = $this->getfiliereStats();
        $filieres_total = $this->count();
        $filieres_filters = $this->getFieldsFilterable();
        $filiere_instance = $this->createInstance();
        $filiere_viewTypes = $this->getViewTypes();
        $filiere_partialViewName = $this->getPartialViewName($filiere_viewType);
        $filiere_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.filiere.stats', $filieres_stats);
    
        $filieres_permissions = [

            'edit-filiere' => Auth::user()->can('edit-filiere'),
            'destroy-filiere' => Auth::user()->can('destroy-filiere'),
            'show-filiere' => Auth::user()->can('show-filiere'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $filieres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($filieres_data as $item) {
                $filieres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'filiere_viewTypes',
            'filiere_viewType',
            'filieres_data',
            'filieres_stats',
            'filieres_total',
            'filieres_filters',
            'filiere_instance',
            'filiere_title',
            'contextKey',
            'filieres_permissions',
            'filieres_permissionsByItem'
        );
    
        return [
            'filieres_data' => $filieres_data,
            'filieres_stats' => $filieres_stats,
            'filieres_total' => $filieres_total,
            'filieres_filters' => $filieres_filters,
            'filiere_instance' => $filiere_instance,
            'filiere_viewType' => $filiere_viewType,
            'filiere_viewTypes' => $filiere_viewTypes,
            'filiere_partialViewName' => $filiere_partialViewName,
            'contextKey' => $contextKey,
            'filiere_compact_value' => $compact_value,
            'filieres_permissions' => $filieres_permissions,
            'filieres_permissionsByItem' => $filieres_permissionsByItem
        ];
    }

}
