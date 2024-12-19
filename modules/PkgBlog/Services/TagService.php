<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgBlog\Services;

use Modules\PkgBlog\Models\Tag;
use Modules\Core\Services\BaseService;

/**
 * Classe TagService pour gérer la persistance de l'entité Tag.
 */
class TagService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour tags.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'name',
        'slug'
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
     * Constructeur de la classe TagService.
     */
    public function __construct()
    {
        parent::__construct(new Tag());
    }

    /**
     * Crée une nouvelle instance de tag.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
