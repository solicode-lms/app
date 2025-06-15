<?php 

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\PkgAutorisation\Models\User;
use Illuminate\Support\Str;

trait BaseModelTrait
{
    public bool $isOwnedByUser = false;
    public string $ownerRelationPath = "";

    /**
     * Contient la déclaration des relation ManyToOne avec pathe de trie, ..
     * @var array
     */
    public $manyToOne = [];

    public function loadBelongsToRelations()
    {
        foreach ($this->attributes as $attribute => $value) {
            // Vérifier si l'attribut se termine par `_id`
            if (Str::endsWith($attribute, '_id')) {
                // Convertir `realisation_projet_id` en `realisationProjet`
                $relationName = Str::camel(substr($attribute, 0, -3));

                // Vérifier si la relation existe dans le modèle
                if (method_exists($this, $relationName)) {
                    $relatedModel = $this->$relationName()->getRelated();
                    $relatedInstance = $relatedModel::find($value);

                    // Associer la relation si un enregistrement est trouvé
                    if ($relatedInstance) {
                        $this->setRelation($relationName, $relatedInstance);
                    }
                }
            }
        }

        return $this;
    }

    public function getNestedValue(string $path)
    {
        $keys = explode('.', $path);
        $value = $this;

        foreach ($keys as $key) {
            if (is_null($value) || !isset($value->$key)) {
                return null;
            }
            $value = $value->$key;
        }

        return $value;
    }


    /**
     * Convertit le modèle Eloquent en tableau associatif
     * sans déclencher de requêtes SQL supplémentaires.
     *
     * Cette méthode ne retourne que :
     * - les attributs du modèle (`attributesToArray`)
     * - les relations déjà chargées explicitement via `with()` ou `load()`
     *
     * ❌ Les relations non chargées ne sont pas accédées, ce qui évite le lazy loading.
     *
     * 🔐 Utile dans les environnements où `Model::preventLazyLoading(true)` est activé.
     *
     * @return array Représentation du modèle et de ses relations chargées sous forme de tableau.
     */
    public function toArrayWithoutLazyLoading(): array
    {
        // Récupère les attributs du modèle (colonnes de la table)
        $array = $this->attributesToArray();

        // Ajoute uniquement les relations déjà chargées (chargées via `with()` par exemple)
        foreach ($this->getRelations() as $relation => $value) {
            // Si c'est une relation multiple (hasMany, etc.), utiliser toArray sur la collection
            // Sinon, sur le modèle unique ou null-safe
            $array[$relation] = $value instanceof \Illuminate\Support\Collection
                ? $value->toArray()
                : $value?->toArray();
        }

        return $array;
    }

}
