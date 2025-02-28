<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait CrudTrait
{

    

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
        return $this->model->withScope(fn() =>  $this->model::all());
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
            $data = $data->getAttributes(); // Convertit l'objet Eloquent en tableau
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
    public function update($id, array $data)
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
                $item->loadBelongsToRelations();
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
     * Récupère un élément à partir de sa référence unique.
     *
     * @param string $reference Le champ de référence unique.
     * @param array $columns Colonnes à récupérer.
     * @return Model|null
     */
    public function getByReference(string $reference, array $columns = ['*'])
    {
        return $this->model->where('reference', $reference)->first($columns);
    }
 

}