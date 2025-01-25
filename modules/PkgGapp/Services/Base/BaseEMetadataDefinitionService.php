<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\Services\Base;

use Modules\PkgGapp\Models\EMetadataDefinition;
use Modules\Core\Services\BaseService;

/**
 * Classe EMetadataDefinitionService pour gérer la persistance de l'entité EMetadataDefinition.
 */
class BaseEMetadataDefinitionService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour eMetadataDefinitions.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'groupe',
        'type',
        'scope',
        'description',
        'default_value'
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
     * Constructeur de la classe EMetadataDefinitionService.
     */
    public function __construct()
    {
        parent::__construct(new EMetadataDefinition());

        // Initialiser les filtres configurables dynamiquement
        $this->fieldsFilterable = [
        ];

    }

    /**
     * Crée une nouvelle instance de eMetadataDefinition.
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
    public function getEMetadataDefinitionStats(): array
    {

        $stats = [];

        

        return $stats;
    }

}
