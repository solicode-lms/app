<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCompetences\Models\Chapitre;
use Modules\Core\Services\BaseService;

/**
 * Classe ChapitreService pour gérer la persistance de l'entité Chapitre.
 */
class BaseChapitreService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour chapitres.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'ordre',
        'nom',
        'lien',
        'description',
        'isOfficiel',
        'unite_apprentissage_id',
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
     * Constructeur de la classe ChapitreService.
     */
    public function __construct()
    {
        parent::__construct(new Chapitre());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCompetences::chapitre.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('chapitre');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('unite_apprentissage_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::uniteApprentissage.plural"), 'unite_apprentissage_id', \Modules\PkgCompetences\Models\UniteApprentissage::class, 'code');
        }

        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }

    }

    /**
     * Crée une nouvelle instance de chapitre.
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
    public function getChapitreStats(): array
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
            'table' => 'PkgCompetences::chapitre._table',
            default => 'PkgCompetences::chapitre._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('chapitre_view_type', $default_view_type);
        $chapitre_viewType = $this->viewState->get('chapitre_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('chapitre_view_type') === 'widgets') {
            $this->viewState->set("scope.chapitre.visible", 1);
        }else{
            $this->viewState->remove("scope.chapitre.visible");
        }
        
        // Récupération des données
        $chapitres_data = $this->paginate($params);
        $chapitres_stats = $this->getchapitreStats();
        $chapitres_filters = $this->getFieldsFilterable();
        $chapitre_instance = $this->createInstance();
        $chapitre_viewTypes = $this->getViewTypes();
        $chapitre_partialViewName = $this->getPartialViewName($chapitre_viewType);
        $chapitre_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.chapitre.stats', $chapitres_stats);
    
        $chapitres_permissions = [

            'edit-chapitre' => Auth::user()->can('edit-chapitre'),
            'destroy-chapitre' => Auth::user()->can('destroy-chapitre'),
            'show-chapitre' => Auth::user()->can('show-chapitre'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $chapitres_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($chapitres_data as $item) {
                $chapitres_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'chapitre_viewTypes',
            'chapitre_viewType',
            'chapitres_data',
            'chapitres_stats',
            'chapitres_filters',
            'chapitre_instance',
            'chapitre_title',
            'contextKey',
            'chapitres_permissions',
            'chapitres_permissionsByItem'
        );
    
        return [
            'chapitres_data' => $chapitres_data,
            'chapitres_stats' => $chapitres_stats,
            'chapitres_filters' => $chapitres_filters,
            'chapitre_instance' => $chapitre_instance,
            'chapitre_viewType' => $chapitre_viewType,
            'chapitre_viewTypes' => $chapitre_viewTypes,
            'chapitre_partialViewName' => $chapitre_partialViewName,
            'contextKey' => $contextKey,
            'chapitre_compact_value' => $compact_value,
            'chapitres_permissions' => $chapitres_permissions,
            'chapitres_permissionsByItem' => $chapitres_permissionsByItem
        ];
    }

}
