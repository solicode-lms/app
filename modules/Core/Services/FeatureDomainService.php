<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services;

use Modules\Core\Models\FeatureDomain;
use Modules\Core\Services\BaseService;

/**
 * Classe FeatureDomainService pour gérer la persistance de l'entité FeatureDomain.
 */
class FeatureDomainService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour featureDomains.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'slug',
        'description',
        'module_id'
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
     * Constructeur de la classe FeatureDomainService.
     */
    public function __construct()
    {
        parent::__construct(new FeatureDomain());
    }

    /**
     * Crée une nouvelle instance de featureDomain.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
