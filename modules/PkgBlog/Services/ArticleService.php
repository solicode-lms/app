<?php

namespace Modules\PkgBlog\Services;

use Modules\PkgBlog\Models\Article;
use Modules\Core\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Classe ArticleService pour gérer la persistance de l'entité Article.
 */
class ArticleService extends BaseService
{

    /**
     * Configure les relations à inclure dans les requêtes.
     *
     * @return void
     */
    // protected function withRelations()
    // {
    //     $this->model = $this->model->with(['category']);
    // }


    /**
     * Les champs de recherche disponibles pour articles.
     *
     * @var array
     */
    protected $fieldsSearchable = [
        'title',
        'slug',
        'content',
        'category_id',
        'user_id',
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
     * Constructeur de la classe ArticleService.
     */
    public function __construct()
    {
        parent::__construct(new Article());
    }

    /**
     * Crée une nouvelle instance d'article.
     *
     * @param array $data Données pour la création.
     * @return mixed
     */
    public function create(array $data)
    {
        return parent::create($data);
    }


    // /**
    //  * Récupère une pagination des articles avec des relations.
    //  *
    //  * @param array $search Critères de recherche.
    //  * @param int $perPage Nombre d'éléments par page.
    //  * @param array $columns Colonnes à récupérer.
    //  * @return LengthAwarePaginator
    //  */
    // public function paginate($search = [], $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    // {
    //     if ($perPage == 0) {
    //         $perPage = $this->paginationLimit;
    //     }

    //     // Ajout des relations si nécessaire
    //     $query = $this->allQuery($search)->with(['category']);

    //     return $query->paginate($perPage, $columns);
    // }

}
