<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgBlog\Services;

use Modules\PkgBlog\Models\Comment;
use Modules\Core\Services\BaseService;

/**
 * Classe CommentService pour gérer la persistance de l'entité Comment.
 */
class CommentService extends BaseService
{
    /**
     * Les champs de recherche disponibles pour comments.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'content',
        'user_id',
        'article_id'
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
     * Constructeur de la classe CommentService.
     */
    public function __construct()
    {
        parent::__construct(new Comment());
    }

    /**
     * Crée une nouvelle instance de comment.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }
}
