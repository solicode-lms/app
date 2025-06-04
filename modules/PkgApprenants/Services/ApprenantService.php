<?php

namespace Modules\PkgApprenants\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Services\ApprenantService\ApprenantServiceWidgets;
use Modules\PkgApprenants\Services\Base\BaseApprenantService;
use Modules\PkgAutorisation\Models\Role;
use Modules\PkgAutorisation\Services\UserService;
use Modules\PkgFormation\Models\Filiere;
use Illuminate\Database\Eloquent\Builder;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class ApprenantService extends BaseApprenantService
{
    use ApprenantServiceWidgets;

    protected $dataSources = [
        "apprenantSansTacheEnCours" => [
            "title" => "Apprenants qui n'ont pas de tâches en cours",
            "method" => "apprenantSansTacheEnCoursQuery"
        ],
        "apprenantAvecTacheEnCours" => [
            "title" => "Apprenants qui ont des tâches en cours",
            "method" => "apprenantAvecTacheEnCoursQuery"
        ],
        "apprenantAvecTacheAFaire" => [
            "title" => "Apprenants qui ont des tâches à faire",
            "method" => "apprenantAvecTacheAFaireQuery"
        ],
        "apprenantSansTacheAFaire" => [
            "title" => "Apprenants qui n'ont pas de tâches à faire",
            "method" => "getApprenantSansTacheAFaireQuery"
        ],
        "apprenantSansTacheTerminee7Jours" => [
            "title" => "Apprenants sans tâches terminé pendant une semaine",
            "method" => "apprenantSansTacheTermineeDepuis7JoursQuery"
        ],
        
    ];
    

    public function find(int $id, array $columns = ['*']){
        return $this->model::withoutGlobalScope('inactif')->find($id);
    }
    // protected function updateRecord(Model $record, array $data): void
    // {
    //     $record->update($data);
    // }
    
    public function dataCalcul($apprenant)
    {
        // En Cas d'édit
        if(isset($apprenant->id)){
          
        }
      
        return $apprenant;
    }

    public function edit(int $id){

        return $this->model::withoutGlobalScope('inactif')->findOrFail($id);
    }


    public function initPassword(int $apprenantId)
    {
        $apprenant = $this->find($apprenantId);
        if (!$apprenant) {
            return false; 
        }
        $userService = new UserService();
        $value = $userService->initPassword($apprenant->user->id);
        return $value;
    }

/**
 * Trouver la liste des apprenants appartenant aux mêmes groupes qu'un apprenant donné.
 *
 * @param int $apprenantId
 * @return \Illuminate\Database\Eloquent\Collection
 */
public function getApprenantsDeGroupe($apprenantId)
{
    return Apprenant::whereHas('groupes', function ($query) use ($apprenantId) {
        $query->whereHas('apprenants', function ($q) use ($apprenantId) {
            $q->where('apprenants.id', $apprenantId);
        });
    })->get();
}




    /**
     * Récupère les apprenants ayant au moins une réalisation de projet
     * dont l'affectation de projet est associée à l'évaluateur donné.
     *
     * @param int $evaluateur_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getApprenantsHasEvaluationRealisationProjetByEvaluateur($evaluateur_id)
    {
        return $this->model::whereHas('realisationProjets.affectationProjet.evaluateurs', function ($query) use ($evaluateur_id) {
            $query->where('evaluateurs.id', $evaluateur_id);
        })->get();
    }
   
}
