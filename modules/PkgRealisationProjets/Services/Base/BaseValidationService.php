<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\Services\Base;

use Modules\PkgRealisationProjets\Models\Validation;
use Modules\Core\Services\BaseService;

/**
 * Classe ValidationService pour gérer la persistance de l'entité Validation.
 */
class BaseValidationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour validations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'transfert_competence_id',
        'note',
        'message',
        'is_valide',
        'realisation_projet_id'
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
     * Constructeur de la classe ValidationService.
     */
    public function __construct()
    {
        parent::__construct(new Validation());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('validation');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('transfert_competence_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgCreationProjet::transfertCompetence.plural"), 'transfert_competence_id', \Modules\PkgCreationProjet\Models\TransfertCompetence::class, 'id');
        }
        if (!array_key_exists('realisation_projet_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgRealisationProjets::realisationProjet.plural"), 'realisation_projet_id', \Modules\PkgRealisationProjets\Models\RealisationProjet::class, 'id');
        }
    }

    /**
     * Crée une nouvelle instance de validation.
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
    public function getValidationStats(): array
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
            'table' => 'PkgRealisationProjets::validation._table',
            default => 'PkgRealisationProjets::validation._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('validation_view_type', $default_view_type);
        $validation_viewType = $this->viewState->get('validation_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('validation_view_type') === 'widgets') {
            $this->viewState->set("filter.validation.visible", 1);
        }
        
        // Récupération des données
        $validations_data = $this->paginate($params);
        $validations_stats = $this->getvalidationStats();
        $validations_filters = $this->getFieldsFilterable();
        $validation_instance = $this->createInstance();
        $validation_viewTypes = $this->getViewTypes();
        $validation_partialViewName = $this->getPartialViewName($validation_viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.validation.stats', $validations_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'validation_viewTypes',
            'validation_viewType',
            'validations_data',
            'validations_stats',
            'validations_filters',
            'validation_instance'
        );
    
        return [
            'validations_data' => $validations_data,
            'validations_stats' => $validations_stats,
            'validations_filters' => $validations_filters,
            'validation_instance' => $validation_instance,
            'validation_viewType' => $validation_viewType,
            'validation_viewTypes' => $validation_viewTypes,
            'validation_partialViewName' => $validation_partialViewName,
            'validation_compact_value' => $compact_value
        ];
    }

}
