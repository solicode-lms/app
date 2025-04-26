<?php
namespace Modules\PkgWidgets\Services\WidgetServiceTraits;

use Exception;
use Illuminate\Database\Eloquent\Collection;

trait WidgetDataSourceExecutor
{
    public function dataSourceExecutor(array $query, $widget)
    {
        $class = "Modules\\" . $widget->model->sysModule->slug . "\\Services\\" . $widget->model->name . "Service";
        $method = $query['dataSource'];

        if (!class_exists($class)) {
            throw new Exception("Classe {$class} introuvable.");
        }

        $service = new $class();

        if (!method_exists($service, $method)) {
            throw new Exception("Méthode {$method} introuvable dans la classe {$class}.");
        }

        $result = $service->$method();
        $widget->count = is_countable($result) ? count($result) : (method_exists($result, 'count') ? $result->count() : 0);


        if (!empty($query['limit']) && is_numeric($query['limit'])) {
            $limit = (int) $query['limit'];

            if ($result instanceof Collection) {
                $result = $result->take($limit); // Utilisation de `take()` pour une Collection Laravel
            } elseif (is_array($result)) {
                $result = array_slice($result, 0, $limit); // Utilisation de `array_slice()` pour un tableau
            }
        }


        return $result;
    }
}
