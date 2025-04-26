<?php


namespace Modules\PkgCreationProjet\Services;
use Modules\PkgCreationProjet\Services\Base\BaseProjetService;

/**
 * Classe ProjetService pour gérer la persistance de l'entité Projet.
 */
class ProjetService extends BaseProjetService
{
    public function dataCalcul($projet)
    {
        // En Cas d'édit
        if(isset($projet->id)){
          
        }
      
        return $projet;
    }

    public function defaultSort($query)
    {
        return $query
            ->withMax('affectationProjets', 'date_fin') // 🔥 Important
            ->orderBy('affectation_projets_max_date_fin', 'desc');
    }
   
}
