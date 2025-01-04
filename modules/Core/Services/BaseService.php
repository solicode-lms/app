<?php

namespace Modules\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Services\Contracts\ServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
 
/**
 * Classe abstraite BaseService qui fournit une implémentation de base
 * pour les opérations courantes de manipulation des données.
 */
abstract class BaseService implements ServiceInterface
{

    protected $contextState;
    /**
     * Configure les relations à inclure dans les requêtes.
     *
     * @return void
     */
    protected function withRelations()
    {
      
    }

    /**
     * Le modèle Eloquent associé à ce référentiel.
     *
     * @var Model
     */
    protected $model;

    /**
     * Limite de pagination par défaut.
     *
     * @var int
     */
    protected $paginationLimit = 10;

    /**
     * Méthode abstraite pour obtenir les champs recherchables.
     *
     * @return array
     */
    abstract public function getFieldsSearchable(): array;

    /**
     * Constructeur de la classe BaseService.
     *
     * @param Model $model Le modèle Eloquent associé au référentiel.
     */
    public function __construct(Model $model){
        $this->model = $model;
        // Scrop management
        $this->contextState = app(ContextState::class);
    }

    /**
     * Renvoie une pagination des résultats.
     *
     * @param array $search Critères de recherche.
     * @param int $perPage Nombre d'éléments par page.
     * @param array $columns Colonnes à récupérer.
     * @return LengthAwarePaginator
     */
    public function paginate($search = [], $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
   
        if ($perPage == 0) { $perPage = $this->paginationLimit;}

        $query = $this->allQuery($search);

        if (is_null($perPage)) {
            $perPage = $this->paginationLimit;
        }
        return $query->paginate($perPage, $columns);
    }

    /**
     * Construit une requête de récupération des données.
     *
     * @param array $search Critères de recherche.
     * @param int|null $skip Nombre d'éléments à ignorer.
     * @param int|null $limit Nombre maximal d'éléments à récupérer.
     * @return Builder
     */
    public function allQuery($search = [], int $skip = null, int $limit = null): Builder
    {
        $query = $this->model->newQuery();

        if (is_array($search)) {
            if (count($search)) {
                foreach ($search as $key => $value) {
                    if (in_array($key, $this->getFieldsSearchable())) {
                        if (!is_null($value)) {
                            $query->where($key, $value);
                        }
                    }
                }
            }
        } else {
            if (!is_null($search)) {
                foreach ($this->getFieldsSearchable() as $searchKey) {
                    $query->orWhere($searchKey, 'LIKE', '%' . $search . '%');
                }
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }

    /** 
     * Effectue une recherche basée sur des critères spécifiques.
     * Essayer d'utiliser this.paginate();
     */
    // public function searchData($searchableData, $perPage = 0)
    // {   
    //     if ($perPage == 0) { $perPage = $this->paginationLimit;}
    //     $query =  $this->allQuery($searchableData);
    //     return $query->get();;
    // }

    /**
     * Renvoie tous les éléments correspondants aux critères donnés.
     *
     * @param array $search Critères de recherche.
     * @param int|null $skip Nombre d'éléments à ignorer.
     * @param int|null $limit Nombre maximal d'éléments à récupérer.
     * @param array $columns Colonnes à récupérer.
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model::all($columns);
    }

    /**
     * Récupère un élément par son identifiant.
     *
     * @param int $id Identifiant de l'élément à récupérer.
     * @param array $columns Colonnes à récupérer.
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']){
        return $this->model->find($id, $columns);
    }

    /**
     * Crée un nouvel élément.
     *
     * @param array $data Données de l'élément à créer.
     * @return mixed
     */
    public function create(array $data){
        return $this->model->create($data);
    }

    /**
     * Met à jour un élément existant.
     *
     * @param mixed $id Identifiant de l'élément à mettre à jour.
     * @param array $data Données à mettre à jour.
     * @return Entity modifié
     */
    public function update($id, array $data)
    {
        $record = $this->model->find($id);

        if (!$record) {
            return false;
        }
        $record->update($data);
        return $record;
    }

    /**
     * Met à jour ou crée un nouvel enregistrement basé sur des critères spécifiques.
     *
     * @param array $attributes Critères pour rechercher l'enregistrement.
     * @param array $values Données à mettre à jour ou à créer.
     * @return Model
     */
    public function updateOrCreate(array $attributes, array $values)
    {
        return $this->model->updateOrCreate($attributes, $values);
    }


    /**
     * Supprime un élément par son identifiant.
     *
     * @param mixed $id Identifiant de l'élément à supprimer.
     * @return bool|null
     */
    public function destroy($id){
        $record = $this->model->find($id);
        // TODO :throw exception if $record is null
        $record->delete();
        return  $record;
    }

    public function createInstance()
    {
        // Créer une nouvelle instance du modèle
        $item = $this->model::make();


        $contextVariables = $this->contextState->all();
    
        // Parcourir les variables de contexte et modifier les attributs correspondants du modèle
        foreach ($contextVariables as $key => $value) {
                if ($item->isFillable($key)) { // Vérifier si l'attribut est fillable
                    $item[$key] = $value;
                }
        }
    
        return $item;
    }

    /**
     * Calcule des statistiques génériques sur une entité.
     *
     * @param array $conditions Conditions optionnelles pour chaque statistique.
     * [
     *   'total' => null, // Pas de condition, retourne le nombre total
     *   'in_stock' => ['status' => 'in-stock'], // Condition pour "En stock"
     *   'out_of_stock' => ['status' => 'out-of-stock'] // Condition pour "Hors stock"
     * ]
     * @return array Statistiques calculées
     */
    public function calculateStats(array $conditions = []): array
    {
        $stats = [];

        foreach ($conditions as $key => $condition) {
            if (is_null($condition)) {
                // Compte total sans condition
                $stats[$key] = $this->model->count();
            } else {
                // Compte basé sur une condition
                $stats[$key] = $this->model->where($condition)->count();
            }
        }

        return $stats;
    }



    /**
 * Calcule les statistiques des entités associées par une relation imbriquée.
 *
 * @param string $relation Relation imbriquée (ex. : 'modules.competences').
 * @param string $countLabel Champ utilisé comme label (ex. : 'code', 'nom').
 * @return array Statistiques des entités liées.
 */
/**
 * Calcule les statistiques pour une relation parent-enfant.
 *
 * @param string $model Nom complet du modèle (ex. : \App\Models\Filiere::class).
 * @param string $relation Nom de la relation à compter (ex. : 'competences').
 * @param string $icon Icône associée à chaque statistique.
 * @return array Statistiques des entités liées.
 */
public function calculateStatsByRelation(string $model, string $relation, string $icon): array
{

    $entities = $model::with($relation)->get();

    return $entities->map(function ($entity) use ($relation, $icon) {
        return [
            'label' => $entity->code, // Supposant que chaque entité a un champ 'nom'
            'value' => $entity->{$relation . '_count'},
            'icon' => $icon,
        ];
    })->toArray();
}

/**
 * Obtenir le total global des compétences.
 *
 * @return array
 */
public function getTotalCompetences(): array
{
    return [
        [
            'icon' => 'fas fa-box',
            'label' => 'Total',
            'value' => $this->model::count(),
        ],
    ];
}


}
