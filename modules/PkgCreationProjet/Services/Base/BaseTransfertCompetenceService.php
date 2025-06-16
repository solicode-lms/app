<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Modules\Core\Services\BaseService;

/**
 * Classe TransfertCompetenceService pour gérer la persistance de l'entité TransfertCompetence.
 */
class BaseTransfertCompetenceService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour transfertCompetences.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'competence_id',
        'niveau_difficulte_id',
        'note',
        'projet_id',
        'question'
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
     * Constructeur de la classe TransfertCompetenceService.
     */
    public function __construct()
    {
        parent::__construct(new TransfertCompetence());
        $this->fieldsFilterable = [];
        $this->title = __('PkgCreationProjet::transfertCompetence.plural');
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('transfertCompetence');
        $this->fieldsFilterable = [];
    

        if (!array_key_exists('competence_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::competence.plural"), 'competence_id', \Modules\PkgCompetences\Models\Competence::class, 'code');
        }

        if (!array_key_exists('niveau_difficulte_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::niveauDifficulte.plural"), 'niveau_difficulte_id', \Modules\PkgCompetences\Models\NiveauDifficulte::class, 'nom');
        }

        if (!array_key_exists('projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::projet.plural"), 'projet_id', \Modules\PkgCreationProjet\Models\Projet::class, 'titre');
        }

    }

    /**
     * Crée une nouvelle instance de transfertCompetence.
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
    public function getTransfertCompetenceStats(): array
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
            'table' => 'PkgCreationProjet::transfertCompetence._table',
            default => 'PkgCreationProjet::transfertCompetence._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->setIfEmpty('transfertCompetence_view_type', $default_view_type);
        $transfertCompetence_viewType = $this->viewState->get('transfertCompetence_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('transfertCompetence_view_type') === 'widgets') {
            $this->viewState->set("scope.transfertCompetence.visible", 1);
        }else{
            $this->viewState->remove("scope.transfertCompetence.visible");
        }
        
        // Récupération des données
        $transfertCompetences_data = $this->paginate($params);
        $transfertCompetences_stats = $this->gettransfertCompetenceStats();
        $transfertCompetences_filters = $this->getFieldsFilterable();
        $transfertCompetence_instance = $this->createInstance();
        $transfertCompetence_viewTypes = $this->getViewTypes();
        $transfertCompetence_partialViewName = $this->getPartialViewName($transfertCompetence_viewType);
        $transfertCompetence_title = $this->title;
        $contextKey = $this->viewState->getContextKey();
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.transfertCompetence.stats', $transfertCompetences_stats);
    
        $transfertCompetences_permissions = [

            'edit-transfertCompetence' => Auth::user()->can('edit-transfertCompetence'),
            'destroy-transfertCompetence' => Auth::user()->can('destroy-transfertCompetence'),
            'show-transfertCompetence' => Auth::user()->can('show-transfertCompetence'),
        ];

        $abilities = ['update', 'delete', 'view'];
        $transfertCompetences_permissionsByItem = [];
        $userId = Auth::id();

        foreach ($abilities as $ability) {
            foreach ($transfertCompetences_data as $item) {
                $transfertCompetences_permissionsByItem[$ability][$item->id] = Gate::check($ability, $item);
            }
        }

        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'transfertCompetence_viewTypes',
            'transfertCompetence_viewType',
            'transfertCompetences_data',
            'transfertCompetences_stats',
            'transfertCompetences_filters',
            'transfertCompetence_instance',
            'transfertCompetence_title',
            'contextKey',
            'transfertCompetences_permissions',
            'transfertCompetences_permissionsByItem'
        );
    
        return [
            'transfertCompetences_data' => $transfertCompetences_data,
            'transfertCompetences_stats' => $transfertCompetences_stats,
            'transfertCompetences_filters' => $transfertCompetences_filters,
            'transfertCompetence_instance' => $transfertCompetence_instance,
            'transfertCompetence_viewType' => $transfertCompetence_viewType,
            'transfertCompetence_viewTypes' => $transfertCompetence_viewTypes,
            'transfertCompetence_partialViewName' => $transfertCompetence_partialViewName,
            'contextKey' => $contextKey,
            'transfertCompetence_compact_value' => $compact_value,
            'transfertCompetences_permissions' => $transfertCompetences_permissions,
            'transfertCompetences_permissionsByItem' => $transfertCompetences_permissionsByItem
        ];
    }

}
