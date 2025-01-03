<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\Services\Base;

use Modules\Core\Models\Feature;
use Modules\Core\Services\BaseService;

/**
 * Classe FeatureService pour gérer la persistance de l'entité Feature.
 */
class BaseFeatureService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour features.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'description',
        'domain_id'
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
     * Constructeur de la classe FeatureService.
     */
    public function __construct()
    {
        parent::__construct(new Feature());
    }

    /**
     * Crée une nouvelle instance de feature.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
