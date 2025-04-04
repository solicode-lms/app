<?php

namespace Modules\PkgWidgets\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\BaseService;
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
                        } elseif (is_array($result)) {
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
                $result = $this->formatTableData($result, $query['tableUI']);
            }
        }
        
       
        if ($widget_utilisateur !== null) {
            if (!empty($widget_utilisateur->titre)) {
                $widget->name = $widget_utilisateur->titre;
            }
            if (!empty($widget_utilisateur->sous_titre)) {
                $widget->label = $widget_utilisateur->sous_titre;
            }
        }

       
        $widget->data = $result;
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
            $queryBuilder->orderBy($query['order_by']['column'], $query['order_by']['direction']);
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
        foreach (['tableUI', 'group_by', 'column','limit','dataSource'] as $key) {
            if (!empty($query['conditions'][$key])) {
                $query[$key] = $query['conditions'][$key];
                unset($query['conditions'][$key]);
            }
        }

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
            throw new Exception("Le paramètre 'tableUI' est requis pour un widget de type table.");
        }
    }

     /**
     * Formate les résultats en fonction de la configuration `tableUI`.
     *
     * @param \Illuminate\Support\Collection $result Résultats bruts de la requête.
     * @param array $tableUI Configuration des colonnes à afficher.
     * @return array Données formatées sous forme de table.
     */
    private function formatTableData($result, array $tableUI)
    {
        return $result->map(function ($item) use ($tableUI) {
            $formattedRow = [];
            foreach ($tableUI as $alias => $column) {
                $formattedRow[$alias] = data_get($item, $column, '');
            }
            return $formattedRow;
        })->toArray();
    }

    // CRUD 

    // public function paginate(array $params = [], int $perPage = 0, array $columns = ['*']): LengthAwarePaginator
    // {
    //     $perPage = $perPage ?: $this->paginationLimit;

    //     return $this->model::withScope(function () use ($params, $perPage, $columns) {
    //         $query = $this->allQuery($params);

           
         
    //         // Calcul du nombre total filtré
    //         $this->totalFilteredCount = $query->count();

    //         return $query->paginate($perPage, $columns);
    //     });
    // }

    // public function create(array|object $data)
    // {
    //     if (is_object($data) && $data instanceof \Illuminate\Database\Eloquent\Model) {
    //         $data = $data->getAttributes();
    //     }

    //     if (!is_array($data)) {
    //         throw new \InvalidArgumentException('Les données doivent être un tableau ou un objet Eloquent.');
    //     }

    //     // Déterminer la position cible
    //     $ordre = $data['ordre'] ?? $this->getNextOrdre();

    //     // Réorganiser les autres si un ordre est explicitement défini
    //     if (isset($data['ordre'])) {
    //         $this->reorderOrdreColumn(null, $ordre);
    //     }

    //     $data['ordre'] = $ordre;

    //     return parent::create($data);
    // }

    // public function update($id, array $data)
    // {
    //     $record = $this->model->find($id);
    
    //     if (!$record) {
    //         return false;
    //     }
    
    //     $ancienOrdre = $record->ordre;
    
    //     if (!isset($data['ordre']) || $data['ordre'] === null) {
    //         $data['ordre'] = $ancienOrdre ?? $this->getNextOrdre();
    //     }
    
    //     $nouvelOrdre = $data['ordre'];
    
    //     // Réorganisation si l’ordre change
    //     if ($nouvelOrdre !== $ancienOrdre) {
    //         $this->reorderOrdreColumn($ancienOrdre, $nouvelOrdre, $record->id);
    //     }
    
    //     return parent::update($id, $data);
    // }
    
  

}
