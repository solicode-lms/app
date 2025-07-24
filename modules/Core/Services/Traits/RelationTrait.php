<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait RelationTrait
{
 
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

            // if (!isset($data[$relation]) || !is_array($data[$relation]) || empty($data[$relation])) {
            //     // Si aucune donnée n'est fournie pour la relation, supprimer toutes les relations existantes
            //     $entity->{$relation}()->sync([]);
            // } else {
            //     // Mettre à jour les relations normalement
            //     $entity->{$relation}()->sync($data[$relation]);
            // }

            if (array_key_exists($relation, $data)) {
            // Si la valeur est null ou tableau vide, on détache tout
            $values = $data[$relation] ?? [];
            $entity->$relation()->sync($values);
            }
            // Si la clé n'existe pas, on ne fait rien
        }
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


}