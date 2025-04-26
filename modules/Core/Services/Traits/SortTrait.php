<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Services\UserModelFilterService;

trait SortTrait
{
 
    /**
     * Applique dynamiquement les tris sur la requête en fonction de la configuration du modèle.
     *
     * Gère :
     * - Le tri par `sortByPath` dans des relations imbriquées.
     * - Le tri simple sur des champs directs.
     * - Le tri par défaut si aucun champ n’est précisé.
     */
    public function applySort($query, $sortFields)
    {
        if ($sortFields) {
            foreach ($sortFields as $field => $direction) {

                // Tente d’appliquer un tri via sortByPath (relations imbriquées)
                if ($this->applySortByPath($query, $field, $direction)) {
                    continue;
                }

                // Sinon, applique un tri classique si le champ est autorisé
                if (in_array($field, $this->getFieldsSortable())) {
                    $query->orderBy($field, $direction);
                }
            }

            return $query;
        }

        return $this->defaultSort($query);
        // Si aucun champ n’est fourni → fallback vers tri par ordre ou updated_at
       
    }

    /**
     * Trie par défault, il est utiliser par la méthode : applySort
     * @param mixed $query
     */
    public function defaultSort($query){
        $model = $query->getModel();
        return Schema::hasColumn($model->getTable(), 'ordre')
            ? $query->orderBy('ordre', 'asc')
            : $query->orderBy('updated_at', 'desc');
    }

    /**
     * Applique le tri si le champ est configuré avec un sortByPath dans manyToOne.
     *
     * Ex : 'etat_realisation_tache_id' → sortByPath = "etatRealisationTache.workflowTache.code"
     * Joint dynamiquement les tables intermédiaires et trie sur la colonne finale.
     */
    protected function applySortByPath($query, $field, $direction): bool
    {
        foreach ($this->model->manyToOne ?? [] as $config) {
            // Vérifie correspondance avec un champ triable via sortByPath
            if (($config['foreign_key'] ?? null) === $field && isset($config['sortByPath'])) {

                // Découpe le chemin "etatRealisationTache.workflowTache.code"
                $segments = explode('.', $config['sortByPath']);
                $finalColumn = array_pop($segments); // "code"

                // Applique les jointures et récupère l’alias de la dernière table
                [$alias, $ok] = $this->resolveJoinSegments($query, $segments);
                if (!$ok) return false;

                // Ajoute les colonnes nécessaires et applique le tri final
                $query->addSelect([
                    "{$this->model->getTable()}.*",
                    "{$alias}.{$finalColumn} as " . str_replace('.', '_', $config['sortByPath'])
                ])->orderBy("{$alias}.{$finalColumn}", $direction);

                return true;
            }
        }

        return false;
    }

    /**
     * Résout dynamiquement les jointures nécessaires à partir d’un chemin relationnel.
     *
     * Exemple : ['etatRealisationTache', 'workflowTache'] → jointures sur ces deux relations
     *
     * @return array [dernierAlias, succès?]
     */
    protected function resolveJoinSegments($query, array $segments): array
    {
        $model = $this->model;
        $baseTable = $model->getTable();
        $alias = $baseTable;
        $aliasMap = [];

        foreach ($segments as $segment) {
            // Recherche de la relation ManyToOne dans le modèle courant
            $relation = $this->getManyToOneRelation($model, $segment);

            // Si la relation est absente ou incomplète → on annule
            if (!$relation || !isset($relation['model'], $relation['relation'])) {
                return [null, false];
            }

            // Déduction du nom de la table et de la clé étrangère
            $table = Str::snake($relation['relation']);
            $foreignKey = $relation['foreign_key'] ?? ($segment . '_id');

            // Génère un alias unique basé sur la chaîne des segments
            $nextAlias = "{$alias}__{$segment}";

            // Jointure seulement si elle n’a pas déjà été faite
            if (!in_array($nextAlias, $aliasMap)) {
                $query->leftJoin("{$table} as {$nextAlias}", "{$alias}.{$foreignKey}", '=', "{$nextAlias}.id");
                $aliasMap[] = $nextAlias;
            }

            // Préparation pour l’étape suivante
            $alias = $nextAlias;
            $model = new $relation['model'];
        }

        return [$alias, true];
    }

    /**
     * Recherche dans la configuration manyToOne une relation correspondant à un segment donné.
     *
     * Prend en compte les noms de clés (`EtatRealisationTache`) ou les noms de relation (`etatRealisationTaches`)
     */
    protected function getManyToOneRelation($model, string $segment): ?array
    {
        return collect($model->manyToOne ?? [])
            ->first(fn($item, $key) =>
                Str::camel($key) === Str::camel($segment) ||
                Str::camel($item['relation'] ?? '') === Str::camel($segment)
            );
    }

}
