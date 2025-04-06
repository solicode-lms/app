<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\Services\Base;

use Modules\PkgAutoformation\Models\Formation;
use Modules\Core\Services\BaseService;

/**
 * Classe FormationService pour gérer la persistance de l'entité Formation.
 */
class BaseFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour formations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom',
        'lien',
        'competence_id',
        'is_officiel',
        'formateur_id',
        'formation_officiel_id',
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
     * Constructeur de la classe FormationService.
     */
    public function __construct()
    {
        parent::__construct(new Formation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('formation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('competence_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCompetences::competence.plural"), 'competence_id', \Modules\PkgCompetences\Models\Competence::class, 'code');
        }
        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }
        if (!array_key_exists('formation_officiel_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgAutoformation::formation.plural"), 'formation_officiel_id', \Modules\PkgAutoformation\Models\Formation::class, 'nom');
        }
    }

    /**
     * Crée une nouvelle instance de formation.
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
    public function getFormationStats(): array
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
            'table' => 'PkgAutoformation::formation._table',
            default => 'PkgAutoformation::formation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('formation_view_type', $default_view_type);
        $formation_viewType = $this->viewState->get('formation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('formation_view_type') === 'widgets') {
            $this->viewState->set("filter.formation.visible", 1);
        }
        
        // Récupération des données
        $formations_data = $this->paginate($params);
        $formations_stats = $this->getformationStats();
        $formations_filters = $this->getFieldsFilterable();
        $formation_instance = $this->createInstance();
        $formation_viewTypes = $this->getViewTypes();
        $formation_partialViewName = $this->getPartialViewName($formation_viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.formation.stats', $formations_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'formation_viewTypes',
            'formation_viewType',
            'formations_data',
            'formations_stats',
            'formations_filters',
            'formation_instance'
        );
    
        return [
            'formations_data' => $formations_data,
            'formations_stats' => $formations_stats,
            'formations_filters' => $formations_filters,
            'formation_instance' => $formation_instance,
            'formation_viewType' => $formation_viewType,
            'formation_viewTypes' => $formation_viewTypes,
            'formation_partialViewName' => $formation_partialViewName,
            'formation_compact_value' => $compact_value
        ];
    }

}
