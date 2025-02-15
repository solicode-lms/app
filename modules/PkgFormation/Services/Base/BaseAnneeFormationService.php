<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Modules\PkgFormation\Models\AnneeFormation;
use Modules\Core\Services\BaseService;

/**
 * Classe AnneeFormationService pour gérer la persistance de l'entité AnneeFormation.
 */
class BaseAnneeFormationService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour anneeFormations.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'titre',
        'date_debut',
        'date_fin'
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
     * Constructeur de la classe AnneeFormationService.
     */
    public function __construct()
    {
        parent::__construct(new AnneeFormation());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];
    }

    /**
     * Crée une nouvelle instance de anneeFormation.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }

    /**
    * Obtenir les statistiques par Relation
    *
    * @return array
    */
    public function getAnneeFormationStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }

}
