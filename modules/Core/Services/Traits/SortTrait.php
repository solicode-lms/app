<?php

namespace Modules\Core\Services\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Core\Services\UserModelFilterService;

trait SortTrait
{
 
    /**
     * Applique un tri dynamique à la requête selon les champs spécifiés.
     *
     * Cette méthode gère :
     * 1. Le tri sur des relations via `sortByPath` défini dans le tableau `manyToOne` du modèle.
     * 2. Le tri sur des champs simples déclarés dans `getFieldsSortable()`.
     * 3. Un tri par défaut (sur `ordre` ou `updated_at`) si aucun champ de tri n'est précisé.
     *
     * 🔄 Résumé de l’algorithme :
     * - Pour chaque champ à trier :
     *   → Si un `sortByPath` est défini, on le découpe en segments.
     *   → Chaque segment est résolu dynamiquement via la propriété `manyToOne` du modèle courant.
     *   → À chaque étape, une jointure `leftJoin` avec alias est ajoutée.
     *   → Le tri est appliqué sur la dernière colonne ciblée par le chemin.
     * - Si aucun `sortByPath` ne correspond, on applique un `orderBy` classique.
     * - Si aucun tri n’est fourni, on utilise un tri par défaut sur `ordre` (s’il existe) ou `updated_at`.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     *        La requête sur laquelle appliquer le tri.
     * @param array|null $sortFields
     *        Tableau associatif des champs à trier avec leur direction (ex: ['etat_realisation_tache_id' => 'asc']).
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     *         La requête modifiée avec les clauses de tri appliquées.
     */
     public function applySort($query, $sortFields)
     {
         if ($sortFields) {
             foreach ($sortFields as $field => $direction) {
     
                 if (property_exists($this->model, 'manyToOne')) {
                     foreach ($this->model->manyToOne as $rootKey => $rootConfig) {
                         if (
                             isset($rootConfig['foreign_key']) &&
                             $rootConfig['foreign_key'] === $field &&
                             isset($rootConfig['sortByPath'])
                         ) {
                             $sortPath = $rootConfig['sortByPath'];
                             $segments = explode('.', $sortPath);
                             $finalColumn = array_pop($segments);
     
                             $baseTable = $this->model->getTable();
                             $previousAlias = $baseTable;
                             $previousModel = $this->model;
                             $aliasMap = [];
     
                             foreach ($segments as $segment) {
                                 $target = collect($previousModel->manyToOne ?? [])
                                     ->first(function ($item, $key) use ($segment) {
                                         return Str::camel($key) === Str::camel($segment) ||
                                                Str::camel($item['relation'] ?? '') === Str::camel($segment);
                                     });
     
                                 if (!$target || !isset($target['model'], $target['relation'])) {
                                     continue 2; // Abandonner ce tri si un segment échoue
                                 }
     
                                 $relationTable = Str::snake($target['relation']);
                                 $foreignKey = $target['foreign_key'] ?? ($segment . '_id');
                                 $currentAlias = "{$previousAlias}__{$segment}";
     
                                 if (!in_array($currentAlias, $aliasMap)) {
                                     $query->leftJoin("{$relationTable} as {$currentAlias}", "{$previousAlias}.{$foreignKey}", '=', "{$currentAlias}.id");
                                     $aliasMap[] = $currentAlias;
                                 }
     
                                 // Utilise le modèle défini dans la config
                                 $previousModel = new $target['model'];
                                 $previousAlias = $currentAlias;
                             }
     
                             $sortAlias = str_replace('.', '_', $sortPath);
                             $query->addSelect([
                                 "{$baseTable}.*",
                                 "{$previousAlias}.{$finalColumn} as {$sortAlias}"
                             ])->orderBy("{$previousAlias}.{$finalColumn}", $direction);
     
                             continue 2;
                         }
                     }
                 }
     
                 // Fallback : tri simple
                 if (in_array($field, $this->getFieldsSortable())) {
                     $query->orderBy($field, $direction);
                 }
             }
     
             return $query;
         }
     
         // Tri par défaut
         $model = $query->getModel();
         return Schema::hasColumn($model->getTable(), 'ordre')
             ? $query->orderBy('ordre', 'asc')
             : $query->orderBy('updated_at', 'desc');
     }
}
