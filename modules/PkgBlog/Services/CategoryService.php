<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgBlog\Services;

use Modules\PkgBlog\Models\Category;
use Modules\Core\Services\BaseService;

/**
 * Classe CategoryService pour gérer la persistance de l'entité Category.
 */
class CategoryService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour categories.
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
     * Constructeur de la classe CategoryService.
     */
    public function __construct()
    {
        parent::__construct(new Category());
    }

    /**
     * Crée une nouvelle instance de category.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
