<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\PkgCompetences\Services;
use Modules\PkgCompetences\Services\Base\BaseCategoryTechnologyService;

/**
 * Classe CategoryTechnologyService pour gérer la persistance de l'entité CategoryTechnology.
 */
class CategoryTechnologyService extends BaseCategoryTechnologyService
{
    public function dataCalcul($categoryTechnology)
    {
        // En Cas d'édit
        if(isset($categoryTechnology->id)){
          
        }
      
        return $categoryTechnology;
    }
   
}
