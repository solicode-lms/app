<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\Services\Base;

use Modules\PkgCreationProjet\Models\Projet;
use Modules\Core\Services\BaseService;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class BaseProjetService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour projets.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'travail_a_faire',
        'critere_de_travail',
        'nombre_jour',
        'description',
        'formateur_id',
        'filiere_id'
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
     * Constructeur de la classe ProjetService.
     */
    public function __construct()
    {
        parent::__construct(new Projet());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('projet');
        $this->fieldsFilterable = [];
    
        if (!array_key_exists('formateur_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::formateur.plural"), 'formateur_id', \Modules\PkgFormation\Models\Formateur::class, 'nom');
        }
        if (!array_key_exists('filiere_id', $scopeVariables)) {
        $this->fieldsFilterable[] = $this->generateManyToOneFilter(__("PkgFormation::filiere.plural"), 'filiere_id', \Modules\PkgFormation\Models\Filiere::class, 'code');
        }
    }

    /**
     * Crée une nouvelle instance de projet.
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
    public function getProjetStats(): array
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
            'table' => 'PkgCreationProjet::projet._table',
            default => 'PkgCreationProjet::projet._table',
        };
    }



    public function prepareDataForIndexView(array $params = []): array
    {
        // Définir le type de vue par défaut
        $default_view_type = 'table';
        $this->viewState->init('projet_view_type', $default_view_type);
        $projet_viewType = $this->viewState->get('projet_view_type', $default_view_type);
    
        // Si viewType = widgets, appliquer filtre visible = 1
        if ($this->viewState->get('projet_view_type') === 'widgets') {
            $this->viewState->set("filter.projet.visible", 1);
        }
        
        // Récupération des données
        $projets_data = $this->paginate($params);
        $projets_stats = $this->getprojetStats();
        $projets_filters = $this->getFieldsFilterable();
        $projet_instance = $this->createInstance();
        $projet_viewTypes = $this->getViewTypes();
        $projet_partialViewName = $this->getPartialViewName($projet_viewType);
    
        // Enregistrer les stats dans le ViewState
        $this->viewState->set('stats.projet.stats', $projets_stats);
    
        // Préparer les variables à injecter dans compact()
        $compact_value = compact(
            'projet_viewTypes',
            'projet_viewType',
            'projets_data',
            'projets_stats',
            'projets_filters',
            'projet_instance'
        );
    
        return [
            'projets_data' => $projets_data,
            'projets_stats' => $projets_stats,
            'projets_filters' => $projets_filters,
            'projet_instance' => $projet_instance,
            'projet_viewType' => $projet_viewType,
            'projet_viewTypes' => $projet_viewTypes,
            'projet_partialViewName' => $projet_partialViewName,
            'projet_compact_value' => $compact_value
        ];
    }

}
