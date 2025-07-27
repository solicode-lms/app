<?php

namespace Modules\PkgApprentissage\Services;

use Modules\PkgApprentissage\Models\EtatRealisationMicroCompetence;
use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Modules\PkgApprentissage\Services\Base\BaseRealisationMicroCompetenceService;
use Modules\PkgCompetences\Services\UniteApprentissageService;

/**
 * Classe RealisationMicroCompetenceService pour gérer la persistance de l'entité RealisationMicroCompetence.
 */
class RealisationMicroCompetenceService extends BaseRealisationMicroCompetenceService
{
    public function dataCalcul($realisationMicroCompetence)
    {
        // En Cas d'édit
        if(isset($realisationMicroCompetence->id)){
          
        }
      
        return $realisationMicroCompetence;
    }

    public function afterCreateRules(RealisationMicroCompetence $realisationMicroCompetence): void
    {
        $realisationUAService = new RealisationUAService();
        $etat_realisation_ua_id = EtatRealisationUa::where('code', "TODO")->value('id');
        $uas = $realisationMicroCompetence->microCompetence->uniteApprentissages;

        foreach ($uas as $ua) {
            // Vérifier si la réalisation UA existe déjà
            $exists = $realisationUAService->model
                ->where('realisation_micro_competence_id', $realisationMicroCompetence->id)
                ->where('unite_apprentissage_id', $ua->id)
                ->exists();

            if (! $exists) {
                $realisationUAService->create([
                    'realisation_micro_competence_id' => $realisationMicroCompetence->id,
                    'unite_apprentissage_id' => $ua->id,
                    'etat_realisation_ua_id' => $etat_realisation_ua_id,
                ]);
            }
        }
    }

  
    /**
     * Récupère ou crée une réalisation de micro-compétence pour un apprenant.
     *
     * @param  int $apprenantId
     * @param  int $microCompetenceId
     * @return RealisationMicroCompetence
     */
    public function getOrCreateByApprenant(int $apprenantId, int $microCompetenceId): RealisationMicroCompetence
    {
        // 1. Chercher si une réalisation existe déjà
        $realisation = $this->model
            ->where('apprenant_id', $apprenantId)
            ->where('micro_competence_id', $microCompetenceId)
            ->first();

        if ($realisation) {
            return $realisation;
        }

        // 2. Créer une nouvelle réalisation avec l'état initial
        $ordreEtatInitial = EtatRealisationMicroCompetence::min('ordre');
        $etatRealisationId = EtatRealisationMicroCompetence::where('ordre', $ordreEtatInitial)->value('id');

        return $this->create([
            'apprenant_id'                    => $apprenantId,
            'micro_competence_id'             => $microCompetenceId,
            'etat_realisation_micro_competence_id' => $etatRealisationId,
            'date_debut' => now(),
        ]);
    }


}
