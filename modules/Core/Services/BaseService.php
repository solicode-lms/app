<?php

namespace Modules\Core\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Services\Contracts\ServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Utils\EloquentRelationHelper;

/**
 * Classe abstraite BaseService qui fournit une implémentation de base
 * pour les opérations courantes de manipulation des données.
 */
abstract class BaseService implements ServiceInterface
{

    protected array $fieldsFilterable;

    protected $viewState;

    /**
     * Le modèle Eloquent associé à ce référentiel.
     *
     * @var Model
     */
    protected $model;

    protected $modelName;

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
        $this->modelName = lcfirst(class_basename($model));
        // Scrop management
        $this->viewState = app(ViewStateService::class);
    }

    // /**
    //  * Configure les relations à inclure dans les requêtes.
    //  *
    //  * @return void
    //  */
    // protected function withRelations()
    // {
      
    // }


    /**
     * Renvoie une pagination des résultats.
     *
     * @param array $search Critères de recherche.
     * @param int $perPage Nombre d'éléments par page.
     * @param array $columns Colonnes à récupérer.
     * @return LengthAwarePaginator
     */
    public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    {
        // Utiliser la limite de pagination par défaut si aucune valeur n'est donnée
        $perPage = $perPage ?: $this->paginationLimit;
    
        // Construire la requête avec `allQuery`
        $query = $this->allQuery($params);
    
        // Retourner la pagination
        return $query->paginate($perPage, $columns);
    }

        
    // TODO : ajouter une recherche sur les relation ManyToOne,
    // TODO : ajouter recherche par nom de filiere : Apprenant, ManyToOne/ManyToOne
    /**
     * Construit une requête de récupération des données.
     *
     * @param array $params Critères de recherche.
     * @return Builder
     */
    public function allQuery(array $params = []): Builder
    {
        $query = $this->model->newQuery();

        // Appliquer la recherche globale
        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params) {
                foreach ($this->getFieldsSearchable() as $field) {
                    $q->orWhere($field, 'LIKE', "%{$params['search']}%");
                }
            });
        }

        // Appliquer les filtres spécifiques (URL aplatie)
        foreach ($params as $field => $value) {
            if (in_array($field, $this->getFieldsSearchable()) && !empty($value)) {
                if (is_numeric($value)) {
                    // Utiliser "=" pour les valeurs numériques
                    $query->where($field, '=', $value);
                } else {
                    // Utiliser "LIKE" pour les chaînes
                    $query->where($field, 'LIKE', "%{$value}%");
                }
            }
        }

      

        // Appliquer le tri multi-colonnes
        if (!empty($params['sort'])) {
            $this->applySort($query,$params['sort']);

            // $sortFields = explode(',', $params['sort']);
            // foreach ($sortFields as $sortField) {

            //     $fieldParts = explode('_', $sortField); // Divise en segments
            //     $direction = end($fieldParts);         // Récupère la direction (dernier élément)
            //     $field = implode('_', array_slice($fieldParts, 0, -1)); // Combine le reste pour former le champ

            //     if (in_array($field, $this->getFieldsSearchable())) {
            //         $query->orderBy($field, $direction);
            //     }
            // }
         }

        return $query;
    }


    public function applySort($query, $sort)
    {
        if ($sort) {
            $sortFields = explode(',', $sort);
    
            foreach ($sortFields as $sortField) {
                $fieldParts = explode('_', $sortField);
                $direction = end($fieldParts);
                $field = implode('_', array_slice($fieldParts, 0, -1));
    
                // Vérifier si le champ est une relation sortable
                $filterableField = collect($this->fieldsFilterable)
                    ->firstWhere('field', $field);
    
                if ($filterableField && isset($filterableField['sortable'])) {
                    [$relationTable, $relationColumn] = explode('.', $filterableField['sortable']);
                    $query->join($relationTable, "{$this->model->getTable()}.{$field}", '=', "{$relationTable}.id")
                            ->select([
                                "{$this->model->getTable()}.*",
                                "{$relationTable}.{$relationColumn} as {$field}_sortable"
                            ])
                            ->orderBy("{$relationTable}.{$relationColumn}", $direction);
                } elseif (in_array($field, $this->getFieldsSearchable())) {
                    // Appliquer un tri normal pour les champs directs
                    $query->orderBy($field, $direction);
                }
            }
        }
    
        return $query;
    }
    



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
    public function create(array|object $data){

        if (is_object($data) && $data instanceof \Illuminate\Database\Eloquent\Model) {
            $data = $data->toArray(); // Convertit l'objet Eloquent en tableau
        }
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Les données doivent être un tableau ou un objet Eloquent.');
        }
        
        $entity = $this->model->create($data);
        $this->syncManyToManyRelations($entity, $data);
        return  $entity;
    }

    /**
     * Met à jour un élément existant.
     *
     * @param mixed $id Identifiant de l'élément à mettre à jour.
     * @param array $data Données à mettre à jour.
     * @return Entity modifié
     */
    public function update($id, array $data): ?Model 
    {
        $record = $this->model->find($id);

        if (!$record) {
            return false;
        }
        $record->update($data);

        $this->syncManyToManyRelations($record, $data);
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

    public function createInstance(array $data = [])
    {
        // Créer une nouvelle instance du modèle
        $item = $this->model::make();
    
        // Récupérer toutes les variables de contexte
        $contextVariables = $this->viewState->getFormVariables($this->modelName);
    
        // Fusionner les données ($data a la priorité sur $contextVariables)
        $mergedData = array_merge($contextVariables, $data);
    
        // Appliquer les valeurs aux champs "fillable" du modèle
        foreach ($mergedData as $key => $value) {
            if ($item->isFillable($key)) { // Vérifier si l'attribut est fillable
                $item->{$key} = $value;
            }
        }

        // Gérer les relations ManyToMany sans les enregistrer en base
        if (property_exists($item, 'manyToMany')) {

          
            
            foreach ($item->manyToMany as $relationConfig) {

                $relation = $relationConfig['relation']; // ex: 'apprenants'
                $foreignKey = $relationConfig['foreign_key']; // ex: 'apprenant_id'

                
                if (isset($mergedData[$relation]) && is_array($mergedData[$relation])) {
                    // Stocker temporairement les relations sans affecter la base de données
                    $item->setRelation($relation, collect($mergedData[$relation]));
                }
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
 * Récupère une collection d'entités via une relation imbriquée, éventuellement filtrée par ID.
 *
 * @param string $model Modèle principal (ex. : \App\Models\Filiere::class).
 * @param string $nestedRelation Chemin de la relation imbriquée (ex. : 'modules.competences').
 * @param int|null $id ID de l'entité principale à filtrer (facultatif).
 * @return \Illuminate\Support\Collection
 */
public function getNestedRelationAsCollection(
    string $model, 
    string $nestedRelation, 
    int $id = null): \Illuminate\Support\Collection
{
    // Charger les entités avec les relations imbriquées
    $query = $model::with($nestedRelation);
    
    // Si un ID est fourni, filtrer par cet ID
    if ($id) {
        $query->where('id', $id);
    }

    $entities = $query->get();

    // Découper la relation imbriquée en segments
    $relations = explode('.', $nestedRelation);

    // Naviguer dans les relations imbriquées
    return $entities->flatMap(function ($entity) use ($relations) {
        $relation = collect([$entity]); // Démarrer avec l'entité encapsulée dans une collection

        foreach ($relations as $segment) {
            // Passer à la relation suivante en fusionnant les résultats
            $relation = $relation->flatMap(function ($item) use ($segment) {
                return $item->{$segment} ?? collect(); // Si la relation est nulle, retourner une collection vide
            });
        }

        return $relation; // Retourner la collection fusionnée
    });
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


public function getStatsByRelation($relationModel,$nestedRelation, $attribute ): array
{
    $stats = [];
    
    // Récupérer toutes les filières
    $relationEntities = $relationModel::all();


    // Parcourir chaque filière pour calculer les compétences par filière
    foreach ($relationEntities as $relationEntity) {
        $entities = $this->getNestedRelationAsCollection(
            $relationModel,
            $nestedRelation,
            $relationEntity->id // Passer l'ID de la filière pour filtrer
        );

        $count = $entities->count();
        if($count > 0) {   
            $stats[] = [
                'icon' => 'fas fa-chart-pie',
                'label' => $relationEntity->{$attribute}, // Code de la filière utilisé comme label
                'value' => $entities->count(),
            ]; 
        }
       
    }

    return $stats;
}



/**
 * Génère un filtre ManyToOne avec des options formatées.
 *
 * @param string $field Le nom du champ.
 * @param string $model La classe du modèle.
 * @return array Le filtre formaté.
 */
protected function generateManyToOneFilter(string $label, string $field, string $model, string $display_field): array
{
    $modelInstance = new $model();

    return [
        'label' => $label,
        'field' => $field,
        'type' => 'ManyToOne',
        'options' => $model::all(['id', $display_field])
            ->map(fn($item) => ['id' => $item['id'], 'label' => $item[$display_field]])
            ->toArray(),
        'sortable' => "{$modelInstance->getTable()}.{$display_field}", // Champ à utiliser pour le tri
    ];
}

protected function generatePolymorphicFilter(string $label, string $field, string $model, string $display_field): array
{
    $modelInstance = new $model();

    return [
        'label' => $label,
        'field' => $field,
        'type' => 'Polymorphic',
        'options' => $model::all(['id', $display_field])
            ->map(fn($item) => ['id' => $item['id'], 'label' => $item[$display_field]])
            ->toArray(),
        'sortable' => "{$modelInstance->getTable()}.{$display_field}", // Champ à utiliser pour le tri
    ];
}

public function initFieldsFilterable(){
    // Il doit être appele aprés le choix de context par index par exemple , pour appliquer 
    // le scope de le contextKey
}

public function getFieldsFilterable(): array
{
    $this->initFieldsFilterable();
    return $this->fieldsFilterable;
}

public function initStats(){

    // Calculer le total global des compétences
    $total = $this->model::count();

    // Initialiser les statistiques avec le total global
    $stats = [
        [
            'icon' => 'fas fa-box',
            'label' => 'Total',
            'value' => $total,
        ],
    ];
    return $stats;
}



    /**
     * Gère la synchronisation des relations ManyToMany définies dans le modèle.
     *
     * @param Model $entity
     * @param array $data
     */
    protected function syncManyToManyRelations(Model $entity, array $data)
    {
        if (!property_exists($entity, 'manyToMany')) {
            return;
        }

        foreach ($entity->manyToMany as $relationConfig) {

            $relation = $relationConfig["relation"];

            if (!isset($data[$relation]) || !is_array($data[$relation]) || empty($data[$relation])) {
                // Si aucune donnée n'est fournie pour la relation, supprimer toutes les relations existantes
                $entity->{$relation}()->sync([]);
            } else {
                // Mettre à jour les relations normalement
                $entity->{$relation}()->sync($data[$relation]);
            }
        }
    }


    public function pushServiceMessage(string $type, string $title, string $message): void
    {
        // Récupérer les messages existants ou initialiser un tableau vide
        $messages = session()->get('service_messages', []);

        // Ajouter un nouveau message au tableau
        $messages[] = [
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ];

        // Stocker la liste mise à jour dans la session avec flash
        session()->flash('service_messages', $messages);
    }


}
