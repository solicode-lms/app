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
use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Modules\PkgCompetences\Services\MicroCompetenceService;

/**
 * Classe ApprenantService pour gérer la persistance de l'entité Apprenant.
 */
class ApprenantService extends BaseApprenantService
{
    use ApprenantServiceWidgets;

     protected array $index_with_relations = ['groupes'];

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
    
   
    public function edit(int $id){

        return $this->model::withoutGlobalScope('inactif')->findOrFail($id);
    }



    public function afterCreateRules(Apprenant $apprenant): void
    {

        // Création de réalisations de micro-compétences pour l'apprenant
        // Maintenant, nous allons créer des réalisations de micro-compétences pour l'apprenant à la création 
        // de réalisation de projet

        // $microCompetenceService = new MicroCompetenceService();
        // $realisationMicroCompetenceService = new RealisationMicroCompetenceService();

        // // Récupérer toutes les micro-compétences via le service

        // // TODO : il faut trouver le groupe de l'année de formation en cours
        // // Récupérer la filière de l'apprenant
        // $filiereId = $apprenant->groupes()->first()?->filiere_id;
        // if (!$filiereId) {
        //         return; // Aucun groupe ou filière pour l'apprenant
        // }

        //  // Récupérer les micro-compétences liées à la filière
        // $microCompetences = $microCompetenceService->model
        // ->whereHas('competence.module', function ($query) use ($filiereId) {
        //     $query->where('filiere_id', $filiereId);
        // })
        // ->get();

        // $etat_realisation_micro_competence_id = EtatRealisationMicroCompetence::where('code', 'TODO')->value('id');

        // foreach ($microCompetences as $mc) {
        //     // Vérifier si la réalisation existe déjà
        //     $exists = $realisationMicroCompetenceService->model
        //         ->where('apprenant_id', $apprenant->id)
        //         ->where('micro_competence_id', $mc->id)
        //         ->exists();

        //     if (! $exists) {
        //         // Créer la réalisation uniquement si elle n'existe pas
        //         $realisationMicroCompetenceService->create([
        //             'apprenant_id' => $apprenant->id,
        //             'micro_competence_id' => $mc->id,
        //             'etat_realisation_micro_competence_id' => $etat_realisation_micro_competence_id,
        //         ]);
        //     }
        // }
    }


    private function getEtatIdByReference(string $reference): int
    {
       
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
