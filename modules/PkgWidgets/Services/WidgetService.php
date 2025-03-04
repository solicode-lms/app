<?php
// add execute()



namespace Modules\PkgWidgets\Services;

use Exception;
use Modules\PkgWidgets\Models\Widget;
use Modules\Core\Services\BaseService;
use Modules\PkgWidgets\Services\Base\BaseWidgetService;

/**
 * Classe WidgetService pour gérer la persistance de l'entité Widget.
 */
class WidgetService extends BaseWidgetService
{


    /**
     * Exécute une requête DSL pour un widget donné.
     *
     * @param array $query
     * @return mixed
     * @throws Exception
     */
    public function execute(array $query)
    {
        if (empty($query['model'])) {
            throw new Exception('Le modèle est requis pour exécuter la requête.');
        }

        if (empty($query['operation'])) {
            throw new Exception('L\'opération est requise pour exécuter la requête.');
        }

        $modelClass = $query['model'];
        if (!class_exists($modelClass)) {
            throw new Exception("Le modèle {$modelClass} n'existe pas.");
        }

        $queryBuilder = $modelClass::query();

        // Appliquer les conditions
        if (!empty($query['conditions'])) {
            foreach ($query['conditions'] as $column => $value) {
                $queryBuilder->where($column, $value);
            }
        }

        // Grouper par colonne
        if (!empty($query['group_by'])) {
            $queryBuilder->groupBy($query['group_by']);
        }

        // Appliquer l'ordre
        if (!empty($query['order_by'])) {
            $queryBuilder->orderBy(
                $query['order_by']['column'],
                $query['order_by']['direction']
            );
        }

        // Limiter les résultats
        if (!empty($query['limit'])) {
            $queryBuilder->limit($query['limit']);
        }

        // Exécuter l'opération
        return match ($query['operation']) {
            'count' => $queryBuilder->count(),
            'sum' => $queryBuilder->sum($query['column'] ?? '*'),
            'average' => $queryBuilder->average($query['column'] ?? '*'),
            'min' => $queryBuilder->min($query['column'] ?? '*'),
            'max' => $queryBuilder->max($query['column'] ?? '*'),
            'getGroupedByColumn' => $queryBuilder->get(),
            'distinct' => $queryBuilder->distinct()->count($query['column'] ?? '*'),
            default => throw new Exception("L'opération {$query['operation']} n'est pas prise en charge."),
        };
    }

    /**
     * Charge et exécute la requête DSL d'un widget.
     *
     * @param \App\Models\Widget $widget
     * @return mixed
     */
    public function executeWidget($widget)
    {
        $query = [
            'model' => $widget->model->model,
            'operation' => $widget->operation->operation,
            'conditions' => json_decode($widget->parameters, true) ?? [],
        ];

        if (!empty($query['conditions']['group_by'])) {
            $query['group_by'] = $query['conditions']['group_by'];
            unset($query['conditions']['group_by']);
        }

        return $this->execute($query);
    }
}

