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
        // Si des champs de tri sont spécifiés
        if ($sortFields) {
            // Parcours de chaque champ à trier
            foreach ($sortFields as $field => $direction) {

                // Vérifie si le modèle courant déclare des relations manyToOne
                if (property_exists($this->model, 'manyToOne')) {

                    // Parcours des relations manyToOne du modèle
                    foreach ($this->model->manyToOne as $rootKey => $rootConfig) {

                        // Si ce champ est une clé étrangère avec un sortByPath défini
                        if (
                            isset($rootConfig['foreign_key']) &&
                            $rootConfig['foreign_key'] === $field &&
                            isset($rootConfig['sortByPath'])
                        ) {
                            // Exemple : "etatRealisationTache.workflowTache.code"
                            $sortPath = $rootConfig['sortByPath'];

                            // On découpe le chemin en segments ["etatRealisationTache", "workflowTache", "code"]
                            $segments = explode('.', $sortPath);
                            $finalColumn = array_pop($segments); // Le dernier segment est la colonne sur laquelle trier

                            $baseTable = $this->model->getTable(); // ex: realisation_taches
                            $previousAlias = $baseTable;          // Pour la première jointure
                            $previousModel = $this->model;        // Modèle initial
                            $aliasMap = [];                       // Suivi des alias déjà utilisés (éviter les doublons)

                            // Résolution de chaque segment comme une relation successive
                            foreach ($segments as $segment) {
                                // Cherche la relation correspondante dans le modèle courant
                                $target = collect($previousModel->manyToOne ?? [])
                                    ->first(function ($item, $key) use ($segment) {
                                        return Str::camel($key) === Str::camel($segment) ||
                                            Str::camel($item['relation'] ?? '') === Str::camel($segment);
                                    });

                                // Si la relation n'existe pas ou est incomplète, on annule ce tri
                                if (!$target || !isset($target['model'], $target['relation'])) {
                                    continue 2; // Passe au champ de tri suivant
                                }

                                // Nom réel de la table cible
                                $relationTable = Str::snake($target['relation']);

                                // Clé étrangère utilisée pour la jointure (par convention ou config)
                                $foreignKey = $target['foreign_key'] ?? ($segment . '_id');

                                // On génère un alias unique pour éviter les conflits
                                $currentAlias = "{$previousAlias}__{$segment}";

                                // Si l’alias n’a pas encore été joint, on ajoute la jointure
                                if (!in_array($currentAlias, $aliasMap)) {
                                    $query->leftJoin("{$relationTable} as {$currentAlias}", "{$previousAlias}.{$foreignKey}", '=', "{$currentAlias}.id");
                                    $aliasMap[] = $currentAlias;
                                }

                                // On passe au modèle suivant dans la chaîne
                                $previousModel = new $target['model'];
                                $previousAlias = $currentAlias;
                            }

                            // Nom d'alias final (utilisé pour la colonne triable)
                            $sortAlias = str_replace('.', '_', $sortPath);

                            // Sélectionne les colonnes du modèle courant + la colonne de tri
                            $query->addSelect([
                                "{$baseTable}.*",
                                "{$previousAlias}.{$finalColumn} as {$sortAlias}"
                            ])->orderBy("{$previousAlias}.{$finalColumn}", $direction);

                            continue 2; // Passe au champ de tri suivant
                        }
                    }
                }

                // Si ce n’est pas une relation, applique un tri simple si autorisé
                if (in_array($field, $this->getFieldsSortable())) {
                    $query->orderBy($field, $direction);
                }
            }

            return $query;
        }

        // Si aucun champ de tri fourni → fallback par défaut
        $model = $query->getModel();
        return Schema::hasColumn($model->getTable(), 'ordre')
            ? $query->orderBy('ordre', 'asc')
            : $query->orderBy('updated_at', 'desc');
    }

}
