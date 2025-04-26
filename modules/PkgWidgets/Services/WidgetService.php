<?php

namespace Modules\PkgWidgets\Services;

use Modules\PkgWidgets\Services\WidgetServiceTraits\WidgetDataSourceExecutor;
use Modules\PkgWidgets\Services\WidgetServiceTraits\WidgetDefaultDesigner;
use Modules\PkgWidgets\Services\WidgetServiceTraits\WidgetLinkGenerator;
use Modules\PkgWidgets\Services\WidgetServiceTraits\WidgetQueryExecutor;
use Modules\PkgWidgets\Services\WidgetServiceTraits\WidgetQueryHelper;
use Modules\PkgWidgets\Services\WidgetServiceTraits\WidgetResultFormatter;

class WidgetService extends Base\BaseWidgetService
{
    use WidgetQueryExecutor,
    WidgetDataSourceExecutor,
    WidgetResultFormatter,
    WidgetDefaultDesigner,
    WidgetLinkGenerator, 
    WidgetQueryHelper;

    public function executeWidget($widget, $widgetUtilisateur = null)
    {
        $query = [
            'model' => $widget->model->model,
            'operation' => $widget->operation->operation,
            'conditions' => json_decode($widget->parameters, true) ?? [],
        ];

        $this->extractSpecialConditions($query, $widget);

        if(!empty($query['dataSource'])){
            $result = $this->dataSourceExecutor($query, $widget);
        }else{
            $result = $this->queryExecutor($query, $widget);
        }
            
        $this->CalculeTotal($query, $widget);
       
        $this->resultFormatter($result, $query, $widget);
        $this->defaultDesigner($widget);
        $this->linkGenerator($widget, $query);
        return $widget;
    }

    private function CalculeTotal($query,$widget){
                // Calcule de Totla : c'est différent de count, le total c'est pour 
        // calculer le pourcentage : count / total
        if (!empty($query['total'])) {
            // Exécuter une requête séparée pour obtenir le total selon les conditions "total"
            $totalQuery = $query; // Copie de la requête originale
            $totalQuery['conditions'] = $query['total']; // On remplace les conditions

            // Supprimer les éléments non nécessaires à la requête "total"
            unset($totalQuery['dataSource'], $totalQuery['limit'], $totalQuery['group_by'], $totalQuery['order_by'], $totalQuery['link'], $totalQuery['tableUI']);

            // Déterminer le modèle
            $modelClass = $totalQuery['model'];

            // Construire la requête
            $queryBuilder = $modelClass::query();

            // Gestion des valeurs dynamiques (comme #apprenant_id)
            if (!empty($totalQuery['conditions'])) {
                $totalQuery['conditions'] = $this->replaceDynamicValues($totalQuery['conditions']);
            }

            $this->filter($queryBuilder, new $modelClass(), $totalQuery['conditions']);

            // Exécution d'une opération count (total = nombre total d'éléments)
            $widget->total = $queryBuilder->count();
        }else{
             // Exécuter une requête séparée pour obtenir le total selon les conditions "total"
             $totalQuery = $query; // Copie de la requête originale
             // Déterminer le modèle
             $modelClass = $totalQuery['model'];
 
             // Construire la requête
             $queryBuilder = $modelClass::query();
 
             // Exécution d'une opération count (total = nombre total d'éléments)
             $widget->total = $queryBuilder->count();
        }
    }
}
