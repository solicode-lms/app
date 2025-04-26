<?php

namespace Modules\PkgWidgets\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\BaseService;
use Modules\Core\Services\SysColorService;
use Modules\PkgWidgets\Services\Base\BaseWidgetService;

/**
 * Service pour la gestion des widgets et l'exécution de requêtes DSL.
 */
class WidgetService extends BaseWidgetService
{

    /**
     * Charge et exécute la requête DSL associée à un widget.
     *
     * @param \Modules\PkgWidgets\Models\Widget $widget Instance du widget.
     * @return mixed Résultat de l'exécution de la requête.
     * @throws Exception Si des paramètres requis sont absents.
     */
    public function executeWidget($widget, $widget_utilisateur = null)
    {
        $query = [
            'model' => $widget->model->model,
            'operation' => $widget->operation->operation,
            'conditions' => json_decode($widget->parameters, true) ?? [],
        ];

        $this->extractSpecialConditions($query);

        $this->validateOperation($query, $widget->type->type);

        if (!empty($query['dataSource'])) {
            $methode = $query['dataSource'];
            $class = "Modules\\" . $widget->model->sysModule->slug . "\\Services\\" . $widget->model->name . "Service";
        
            if (class_exists($class)) {
                $service = new $class();
        
                if (method_exists($service, $methode)) {
                    $result = $service->$methode();
                    $widget->count = is_countable($result) ? count($result) : (method_exists($result, 'count') ? $result->count() : 0);

                    if (!empty($query['limit']) && is_numeric($query['limit'])) {
                        $limit = (int) $query['limit'];

                        if ($result instanceof Collection) {
                            $result = $result->take($limit); // Utilisation de `take()` pour une Collection Laravel
                            if ($widget->type->type === "table" && isset($query['tableUI'])) {
                                $result = $this->formatCollectionToTableData($result, $query['tableUI']);
                            }
                        } elseif (is_array($result)) {
                            $result = $this->formatArrayToTableData($result, $query['tableUI']);
                            $result = array_slice($result, 0, $limit); // Utilisation de `array_slice()` pour un tableau
                        }
                    }

                } else {
                    throw new Exception("Méthode '$methode' introuvable dans la classe '$class'.");
                }
            } else {
                throw new Exception("Classe '$class' introuvable.");
            }
        } else {
            $result = $this->execute($query, $widget);
          // Si le type est "table", formater les données en utilisant tableUI
            if ($widget->type->type === "table" && isset($query['tableUI'])) {
                    $result = $this->formatCollectionToTableData($result, $query['tableUI']);
                }
            
        }


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

      
        // Utiliser les titre et sous-titre utilisateur
        // if ($widget_utilisateur !== null) {
        //     if (!empty($widget_utilisateur->titre)) {
        //         $widget->name = $widget_utilisateur->titre;
        //     }
        //     if (!empty($widget_utilisateur->sous_titre)) {
        //         $widget->label = $widget_utilisateur->sous_titre;
        //     }
        // }

        // 🎨 Appliquer la couleur par défaut du modèle si aucune couleur définie sur le widget
        if (empty($widget->sysColor) && !empty($widget->model?->sysColor)) {
            $widget->sysColor = $widget->model->sysColor;
            
        }

        // 🖼️ Appliquer l'icône par défaut du modèle si aucune icône définie sur le widget
        if (empty($widget->icon) && !empty($widget->model?->icone)) {
            $widget->icon = $widget->model->icone;
        }
        $widget->sysColor->textColor = (new SysColorService())->getTextColorForBackground($widget->sysColor->hex);
        $widget->data = $result;

        // Calcule de lien
        if (!empty($query['link']) && !empty($query['link']['route_name'])) {

            

            $routeName = $query['link']['route_name'];
            $params = $query['link']['route_params'] ?? [];
            // fix user id params
            foreach ($params as $key => $value) {
                if ($value === '#apprenant_id') {
                    $params[$key] = $this->sessionState->get("apprenant_id");
                }
                if ($value === '#user_id') {
                    $params[$key] = $this->sessionState->get("user_id");
                }
                if ($value === '#formateur_id') {
                    $params[$key] = $this->sessionState->get("formateur_id");
                }
            }
            $widget->link = route($routeName, $params);
        }

        


       
        return $widget;
    }
    /**
     * Exécute une requête DSL pour un widget donné.
     *
     * @param array $query Données de la requête, incluant le modèle, l'opération et les conditions.
     * @return mixed Résultat de l'exécution de la requête.
     * @throws Exception Si des paramètres requis sont absents ou invalides.
     */
    public function execute(array $query,$widget)
    {
        $this->validateQuery($query);

        $modelClass = $query['model'];

        // Gestion des valeurs dynamiques
        if (!empty($query['conditions'])) {
            $query['conditions'] = $this->replaceDynamicValues($query['conditions']);
        }

        $queryBuilder = $modelClass::query();
        $this->filter($queryBuilder, new $modelClass(), $query['conditions']);

        $this->applyQueryModifiers($queryBuilder, $query,$widget);

        return $this->performOperation($queryBuilder, $query);
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
     * Applique les filtres et options de la requête.
     *
     * @param \Illuminate\Database\Eloquent\Builder $queryBuilder Constructeur de requête.
     * @param array $query Données de la requête.
     */
    private function applyQueryModifiers($queryBuilder, array $query,$widget)
    {
        if (!empty($query['group_by'])) {
            $queryBuilder->groupBy($query['group_by']);
        }

        if (!empty($query['order_by'])) {
            $this->applySort($queryBuilder, [$query['order_by']['column'] => $query['order_by']['direction'] ]);
          //  $queryBuilder->orderBy($query['order_by']['column'], $query['order_by']['direction']);
        }

        $widget->count= $queryBuilder->count();

        if (!empty($query['limit'])) {
            $queryBuilder->limit($query['limit']);
        }
    }

    /**
     * Exécute l'opération demandée sur la requête.
     *
     * @param \Illuminate\Database\Eloquent\Builder $queryBuilder Constructeur de requête.
     * @param array $query Données de la requête.
     * @return mixed Résultat de l'opération.
     * @throws Exception Si l'opération demandée n'est pas supportée.
     */
    private function performOperation($queryBuilder, array $query)
    {
        return match ($query['operation']) {
            'count' => $queryBuilder->count(),
            'sum' => $queryBuilder->sum($query['column']),
            'average' => $queryBuilder->average($query['column']),
            'min' => $queryBuilder->min($query['column']),
            'max' => $queryBuilder->max($query['column']),
            'parameters' => $queryBuilder->get(),
            'distinct' => $queryBuilder->distinct()->count($query['column']),
            default => throw new Exception("L'opération {$query['operation']} n'est pas prise en charge."),
        };
    }

    /**
     * Extrait les conditions spécifiques de la requête en fonction du contexte et du rôle de l'utilisateur.
     *
     * @param array &$query Référence à la requête modifiée.
     */
    private function extractSpecialConditions(array &$query)
    {
      

        // Gestion des conditions selon le rôle de l'utilisateur
        if (!empty($query['conditions']['roles'])) {
            $userRole = $this->sessionState->get("user_role"); // Récupération du rôle utilisateur

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
    }

    /**
     * Vérifie si les paramètres requis sont bien fournis pour l'opération demandée.
     *
     * @param array $query Requête en cours de validation.
     * @param string $widgetType Type du widget (ex. table).
     * @throws Exception Si un paramètre obligatoire est absent.
     */
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

     /**
     * Formate les résultats en fonction de la configuration `tableUI`.
     *
     * @param \Illuminate\Support\Collection $result Résultats bruts de la requête.
     * @param array $tableUI Configuration des colonnes à afficher.
     * @return array Données formatées sous forme de table.
     */
    private function formatCollectionToTableData($result, array $tableUI)
    {
        // Trier selon l'ordre défini
        usort($tableUI, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
        $sysColorService = new SysColorService();
    
        return $result->map(function ($item) use ($tableUI,$sysColorService) {
            $formattedRow = [];
    
            foreach ($tableUI as $columnConfig) {
                $label = $columnConfig['label'];
                $path = $columnConfig['key'];
                $nature = $columnConfig['nature'] ?? "String";
    
                $value = method_exists($item, 'getNestedValue')
                    ? $item->getNestedValue($path)
                    : data_get($item, $path, '');
    
                $formattedRow[$label] = $value;

                switch ($nature) {
                    case "String":{
                        $formattedRow[$label] = [
                            'value' => $value,
                            'nature' => $nature
                        ];
                        break;
                    }
                    case "badge": {
                        $couleur_path = $columnConfig['couleur'];
                        $couleur = method_exists($item, 'getNestedValue')
                        ? $item->getNestedValue($couleur_path)
                        : data_get($item, $path, '');

                        $formattedRow[$label] = [
                            'value' => $value,
                            'nature' => $nature,
                            'couleur' => $couleur,
                            'textCouleur' => $sysColorService->getTextColorForBackground($couleur)
                        ];
                        break;
                    }
                    default : {
                        $formattedRow[$label] = [
                            'value' => $value,
                            'nature' => $nature
                        ];
                    }  
                }

            }
    
            return $formattedRow;
        })->toArray();
    }
    

    /**
     * Formate un tableau brut en fonction de la configuration `tableUI`.
     *
     * @param array $data Données à formater (tableau d’objets ou d’associatifs).
     * @param array $tableUI Configuration des colonnes à afficher.
     * @return array Données formatées sous forme de table.
     */
    private function formatArrayToTableData(array $data, array $tableUI): array
    {
        // Trier selon l'ordre défini
        usort($tableUI, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    
        return array_map(function ($item) use ($tableUI) {
            $formattedRow = [];
    
            foreach ($tableUI as $columnConfig) {
                $label = $columnConfig['label'];
                $path = $columnConfig['key'];
    
                $value = is_array($item) || is_object($item)
                    ? data_get($item, $path)
                    : null;
    
                $formattedRow[$label] = $value;
            }
    
            return $formattedRow;
        }, $data);
    }
    

  

}
