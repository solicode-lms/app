<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\IPackage;
use Modules\Core\Services\BaseService;

/**
 * Classe IPackageService pour gérer la persistance de l'entité IPackage.
 */
class BaseIPackageService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour iPackages.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
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
     * Constructeur de la classe IPackageService.
     */
    public function __construct()
    {
        parent::__construct(new IPackage());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de iPackage.
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
    public function getIPackageStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
