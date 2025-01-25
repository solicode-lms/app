<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EModel;
use Modules\Core\Services\BaseService;

/**
 * Classe EModelService pour gérer la persistance de l'entité EModel.
 */
class BaseEModelService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eModels.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'table_name',
        'icon',
        'is_pivot_table',
        'description',
        'e_package_id'
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
     * Constructeur de la classe EModelService.
     */
    public function __construct()
    {
        parent::__construct(new EModel());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
            $this->generateManyToOneFilter(__("PkgGapp::ePackage.plural"), 'e_package_id', \Modules\PkgGapp\Models\EPackage::class, 'name'),
        ];

    }

    /**
     * Crée une nouvelle instance de eModel.
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
    public function getEModelStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
