<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Modules\PkgApprenants\Models\NiveauxScolaire;
use Modules\Core\Services\BaseService;

/**
 * Classe NiveauxScolaireService pour gérer la persistance de l'entité NiveauxScolaire.
 */
class BaseNiveauxScolaireService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour niveauxScolaires.
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
     * Constructeur de la classe NiveauxScolaireService.
     */
    public function __construct()
    {
        parent::__construct(new NiveauxScolaire());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('niveauxScolaire');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de niveauxScolaire.
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
    public function getNiveauxScolaireStats(): array
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
            'table' => 'PkgApprenants::niveauxScolaire._table',
            default => 'PkgApprenants::niveauxScolaire._table',
        };
    }

}
