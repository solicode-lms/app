<?php


namespace Modules\PkgCompetences\Services;

use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Services\RealisationMicroCompetenceService;
use Modules\PkgCompetences\Services\Base\BaseMicroCompetenceService;

/**
 * Classe MicroCompetenceService pour gérer la persistance de l'entité MicroCompetence.
 */
class MicroCompetenceService extends BaseMicroCompetenceService
{


    public function startFormation(int $microCompetenceId)
    {
        $microCompetence = $this->find($microCompetenceId);
        if (!$microCompetence) {
            return false;
        }

        $apprenantId = auth()->user()->apprenant->id ?? null;
        if (!$apprenantId) {
            $this->pushServiceMessage("error", "Accès refusé", "Aucun apprenant connecté.");
            return false;
        }

        $alreadyExists = RealisationMicroCompetence::where('micro_competence_id', $microCompetenceId)
            ->where('apprenant_id', $apprenantId)
            ->exists();

        if ($alreadyExists) {
            $this->pushServiceMessage("info", "Formation déjà commencée", "Tu as déjà une instance de suivi pour cette micro-compétence.");
            return true;
        }

        (new RealisationMicroCompetenceService())->create([
            'apprenant_id' => $apprenantId,
            'micro_competence_id' => $microCompetenceId,
            'date_debut' => now()
        ]);

        $this->pushServiceMessage("success", "Formation lancée", "Tu peux maintenant suivre la formation pas à pas.");

        return true;
    }


    public function dataCalcul($microCompetence)
    {
        // En Cas d'édit
        if(isset($microCompetence->id)){
          
        }
      
        return $microCompetence;
    }
   
}
