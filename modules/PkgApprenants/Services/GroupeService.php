<?php
 

namespace Modules\PkgApprenants\Services;
use Modules\PkgApprenants\Services\Base\BaseGroupeService;

/**
 * Classe GroupeService pour gérer la persistance de l'entité Groupe.
 */
class GroupeService extends BaseGroupeService
{

    protected array $index_with_relations = ['filiere','formateurs'];

    /**
     * Récupère la liste des groupes ayant au moins une affectation de projet contenant des évaluateurs.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGroupesAvecAffectationProjetEvaluateurs()
    {
        return $this->model::whereHas('affectationProjets.evaluateurs')->get();
    }


    


   
}
