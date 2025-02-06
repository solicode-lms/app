<?php 

namespace Modules\Core\Utils;

use Illuminate\Database\Eloquent\Model;

class EloquentRelationHelper
{
    /**
     * Synchronise dynamiquement les relations ManyToMany d'un modèle Eloquent.
     *
     * @param Model $entity  Instance du modèle
     * @param array $data    Données envoyées, contenant éventuellement des relations
     */
    public static function syncManyToManyRelations(Model $entity, array $data)
    {
        $manyToManyRelations = self::getManyToManyRelations($entity);

        foreach ($manyToManyRelations as $relation) {
            if (isset($data[$relation]) && is_array($data[$relation])) {
                $entity->{$relation}()->sync($data[$relation]);
            }
        }
    }

    /**
     * Détecte dynamiquement les relations ManyToMany d'un modèle Eloquent.
     *
     * @param Model $entity  Instance du modèle
     * @return array         Liste des noms des relations ManyToMany
     */
    public static function getManyToManyRelations(Model $entity): array
    {
        $manyToManyRelations = [];

        foreach (get_class_methods($entity) as $method) {
            if (method_exists($entity, $method)) {
                $reflection = new \ReflectionMethod($entity, $method);
                if ($reflection->getNumberOfParameters() === 0) {
                    $returnType = $reflection->invoke($entity);
                    if ($returnType instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                        $manyToManyRelations[] = $method;
                    }
                }
            }
        }

        return $manyToManyRelations;
    }
}
