<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait CrudCreateTrait
{

    
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
     * Crée un nouvel élément.
     *
     * @param array $data Données de l'élément à créer.
     * @return mixed
     */
    public function create(array|object $data){
        $this->executeRules('before', 'create', $data, null);
        if (is_object($data) && $data instanceof \Illuminate\Database\Eloquent\Model) {
            $data = $data->getAttributes(); // Convertit l'objet Eloquent en tableau
        }
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Les données doivent être un tableau ou un objet Eloquent.');
        }

        // Si le modèle a une colonne "ordre"
        if ($this->hasOrdreColumn()) {
            $ordre = $data['ordre'] ?? $this->getNextOrdre();

            // Réorganiser les autres si l’ordre est explicitement fourni
            if (isset($data['ordre'])) {
                if($this->ordreGroupColumn){
                    $this->reorderOrdreColumn(null, $ordre, null, $data[$this->ordreGroupColumn]);
                }else{
                    $this->reorderOrdreColumn(null, $ordre, null, null);
                }
               
            }

            $data['ordre'] = $ordre;
        }
        
        $entity = $this->model->create($data);
        $this->syncManyToManyRelations($entity, $data);

        $this->executeRules('after', 'delete', $entity, $entity->id);
        return  $entity;
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
}