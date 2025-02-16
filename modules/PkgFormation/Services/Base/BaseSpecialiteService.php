<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\Services\Base;

use Modules\PkgFormation\Models\Specialite;
use Modules\Core\Services\BaseService;

/**
 * Classe SpecialiteService pour gérer la persistance de l'entité Specialite.
 */
class BaseSpecialiteService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour specialites.
     *
     * @var array
     */
    protected $fieldsSearchable = [
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
     * Constructeur de la classe SpecialiteService.
     */
    public function __construct()
    {
        parent::__construct(new Specialite());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];
    }

    /**
     * Crée une nouvelle instance de specialite.
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
    public function getSpecialiteStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
