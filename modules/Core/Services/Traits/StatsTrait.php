<?php

namespace Modules\Core\Services\Traits;


trait StatsTrait
{
 
    
public function initStats(){

    // Calculer le total global des compétences
    $total = $this->model::count();

    // Initialiser les statistiques avec le total global
    $stats = [
        [
            'icon' => 'fas fa-box',
            'label' => 'Total',
            'value' => $total,
        ],
    ];
    return $stats;
}



    /**
     * Calcule des statistiques génériques sur une entité.
     *
     * @param array $conditions Conditions optionnelles pour chaque statistique.
     * [
     *   'total' => null, // Pas de condition, retourne le nombre total
     *   'in_stock' => ['status' => 'in-stock'], // Condition pour "En stock"
     *   'out_of_stock' => ['status' => 'out-of-stock'] // Condition pour "Hors stock"
     * ]
     * @return array Statistiques calculées
     */
    public function calculateStats(array $conditions = []): array
    {
        $stats = [];

        foreach ($conditions as $key => $condition) {
            if (is_null($condition)) {
                // Compte total sans condition
                $stats[$key] = $this->model->count();
            } else {
                // Compte basé sur une condition
                $stats[$key] = $this->model->where($condition)->count();
            }
        }

        return $stats;
    }

    

public function getStatsByRelation($relationModel,$nestedRelation, $attribute ): array
{
    $stats = [];
    
    // Récupérer toutes les filières
    $relationEntities = $relationModel::all();


    // Parcourir chaque filière pour calculer les compétences par filière
    foreach ($relationEntities as $relationEntity) {
        $entities = $this->getNestedRelationAsCollection(
            $relationModel,
            $nestedRelation,
            $relationEntity->id // Passer l'ID de la filière pour filtrer
        );

        $count = $entities->count();
        if($count > 0) {   
            $stats[] = [
                'icon' => 'fas fa-chart-pie',
                'label' => $relationEntity->{$attribute}, // Code de la filière utilisé comme label
                'value' => $entities->count(),
            ]; 
        }
       
    }

    return $stats;
}


}