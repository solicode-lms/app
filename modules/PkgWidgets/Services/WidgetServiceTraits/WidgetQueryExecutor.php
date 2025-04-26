<?php
namespace Modules\PkgWidgets\Services\WidgetServiceTraits;

use Exception;
use Modules\Core\Services\SessionState;

trait WidgetQueryExecutor
{
   
    public function queryExecutor(array $query, $widget)
    {
        $this->validateQuery($query);

        $modelClass = $query['model'];
        $queryBuilder = $modelClass::query();

        if (!empty($query['conditions'])) {
            $query['conditions'] = $this->replaceDynamicValues($query['conditions']);
        }

        $this->filter($queryBuilder, new $modelClass(), $query['conditions']);


        if (!empty($query['group_by'])) {
            $queryBuilder->groupBy($query['group_by']);
        }

        if (!empty($query['order_by'])) {
            $this->applySort($queryBuilder, [$query['order_by']['column'] => $query['order_by']['direction'] ]);
        }

        $widget->count = $queryBuilder->count();

        if (!empty($query['limit'])) {
            $queryBuilder->limit($query['limit']);
        }

        return match ($query['operation']) {
            'count' => $queryBuilder->count(),
            'sum' => $queryBuilder->sum($query['column']),
            'average' => $queryBuilder->average($query['column']),
            'min' => $queryBuilder->min($query['column']),
            'max' => $queryBuilder->max($query['column']),
            'parameters' => $queryBuilder->get(),
            'distinct' => $queryBuilder->distinct()->count($query['column']),
            default => throw new Exception("Opération {$query['operation']} non supportée."),
        };
    }

    /**
     * Remplace les valeurs dynamiques dans les conditions.
     *
     * @param array $conditions Conditions de la requête.
     * @return array Conditions mises à jour.
     */
    private function replaceDynamicValues(array $conditions)
    {
        foreach ($conditions as $column => $value) {
            if ($value === '#user_id') {
                $conditions[$column] = $this->sessionState->get("user_id");
            }
            if ($value === '#apprenant_id') {
                $conditions[$column] = $this->sessionState->get("apprenant_id");
            }

            if ($value === '#formateur_id') {
                $conditions[$column] = $this->sessionState->get("formateur_id");
            }
        }
        return $conditions;
    }


        /**
     * Valide la structure de la requête.
     *
     * @param array $query Requête à valider.
     * @throws Exception Si un paramètre essentiel est manquant ou incorrect.
     */
    private function validateQuery(array $query)
    {
        if (empty($query['model'])) {
            throw new Exception('Le modèle est requis pour exécuter la requête.');
        }

        if (empty($query['operation'])) {
            throw new Exception('L\'opération est requise pour exécuter la requête.');
        }

        if (!class_exists($query['model'])) {
            throw new Exception("Le modèle {$query['model']} n'existe pas.");
        }
    }

}
