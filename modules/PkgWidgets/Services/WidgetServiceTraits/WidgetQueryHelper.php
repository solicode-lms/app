<?php
namespace Modules\PkgWidgets\Services\WidgetServiceTraits;

use Exception;

trait WidgetQueryHelper
{
    public function extractSpecialConditions(array &$query,$widget,)
    {
        // Gestion des conditions selon le rôle de l'utilisateur
        if (!empty($query['conditions']['roles'])) {
          
            $userRole =  $this->sessionState->get("user_role");
            if (!empty($query['conditions']['roles'][$userRole])) {
                foreach ($query['conditions']['roles'][$userRole] as $key => $value) {
                    $query['conditions'][$key] = $value;
                }
            }

            unset($query['conditions']['roles']); // Suppression après traitement
        }

        foreach (['total', 'link','tableUI', 'group_by','order_by' ,'column','limit','dataSource'] as $key) {
            if (!empty($query['conditions'][$key])) {
                $query[$key] = $query['conditions'][$key];
                unset($query['conditions'][$key]);
            }
        }

        $this->validateOperation($query,$widget->type->type);
    }

    private function validateOperation(array $query, string $widgetType)
    {
        $columnRequiredOperations = ['sum', 'average', 'min', 'max', 'distinct'];

        if (in_array($query['operation'], $columnRequiredOperations) && !isset($query['column'])) {
            throw new Exception("Le paramètre 'column' est requis pour l'opération '{$query['operation']}'.");
        }

        if ($widgetType === "table" && !isset($query['tableUI'])) {
            throw new Exception("Le paramètre 'tableUI' est requis pour un widget de type table." . json_encode($query));
        }
    }
}
