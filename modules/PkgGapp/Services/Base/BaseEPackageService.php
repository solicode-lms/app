<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EPackage;
use Modules\Core\Services\BaseService;

/**
 * Classe EPackageService pour gérer la persistance de l'entité EPackage.
 */
class BaseEPackageService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour ePackages.
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
     * Constructeur de la classe EPackageService.
     */
    public function __construct()
    {
        parent::__construct(new EPackage());
        $this->fieldsFilterable = [];
    }

    public function initFieldsFilterable(){
       // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];
    }

    /**
     * Crée une nouvelle instance de ePackage.
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
    public function getEPackageStats(): array
    {

        $stats = $this->initStats();

        

        return $stats;
    }



}
