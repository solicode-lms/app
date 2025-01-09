<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\IModel;
use Modules\Core\Services\BaseService;

/**
 * Classe IModelService pour gérer la persistance de l'entité IModel.
 */
class BaseIModelService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour iModels.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'icon',
        'description',
        'i_package_id'
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
     * Constructeur de la classe IModelService.
     */
    public function __construct()
    {
        parent::__construct(new IModel());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de iModel.
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
    public function getIModelStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
