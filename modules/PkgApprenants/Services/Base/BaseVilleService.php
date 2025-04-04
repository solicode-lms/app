<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\Services\Base;

use Modules\PkgApprenants\Models\Ville;
use Modules\Core\Services\BaseService;

/**
 * Classe VilleService pour gérer la persistance de l'entité Ville.
 */
class BaseVilleService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour villes.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'nom'
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
     * Constructeur de la classe VilleService.
     */
    public function __construct()
    {
        parent::__construct(new Ville());
        $this->fieldsFilterable = [];
    }


    public function initFieldsFilterable()
    {
        // Initialiser les filtres configurables dynamiquement
        $scopeVariables = $this->viewState->getScopeVariables('ville');
        $this->fieldsFilterable = [];
    
    }

    /**
     * Crée une nouvelle instance de ville.
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
    public function getVilleStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
