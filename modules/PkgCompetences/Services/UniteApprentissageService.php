<?php


namespace Modules\PkgCompetences\Services;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Modules\PkgCompetences\Services\Base\BaseUniteApprentissageService;

/**
 * Classe UniteApprentissageService pour gérer la persistance de l'entité UniteApprentissage.
 */
class UniteApprentissageService extends BaseUniteApprentissageService
{

        protected $dataSources = [
        "apprenantSansTacheEnCours" => [
            "title" => "Unité d'apprentissage non alignée",
            "method" => "uniteApprentissageNonAligneeQuery"
        ],
    ];


    public function dataCalcul($uniteApprentissage)
    {
        // En Cas d'édit
        if(isset($uniteApprentissage->id)){
          
        }
      
        return $uniteApprentissage;
    }


     public function uniteApprentissageNonAligneeQuery(): Builder
    {
       

        return $query;
    }
   
}
