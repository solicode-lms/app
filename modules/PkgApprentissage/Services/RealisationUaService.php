<?php


namespace Modules\PkgApprentissage\Services;

use Illuminate\Support\Facades\DB;
use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Modules\PkgApprentissage\Models\RealisationUa;
use Modules\PkgApprentissage\Services\Base\BaseRealisationUaService;
use Modules\PkgCompetences\Models\UniteApprentissage;

/**
 * Classe RealisationUaService pour gérer la persistance de l'entité RealisationUa.
 */
class RealisationUaService extends BaseRealisationUaService
{
    public function dataCalcul($realisationUa)
    {
        // En Cas d'édit
        if(isset($realisationUa->id)){
          
        }
      
        return $realisationUa;
    }

    public function afterCreateRules(RealisationUa $realisationUa): void
    {
        // Ajouter automatiquement les réalisations des chapitres liés à l'unité d'apprentissage
        $realisationChapitreService = new RealisationChapitreService();
        $etat_realisation_chapitre_id = EtatRealisationChapitre::where('code', "TODO")->value('id');
        $chapitres = $realisationUa->uniteApprentissage->chapitres;

        foreach ($chapitres as $chapitre) {
            // Vérifier si la réalisation du chapitre existe déjà
            $exists = $realisationChapitreService->model
                ->where('realisation_ua_id', $realisationUa->id)
                ->where('chapitre_id', $chapitre->id)
                ->exists();

            if (! $exists) {
                $realisationChapitreService->create([
                    'realisation_ua_id' => $realisationUa->id,
                    'chapitre_id' => $chapitre->id,
                    'etat_realisation_chapitre_id' => $etat_realisation_chapitre_id,
                ]);
            }
        }
    }





    /**
     * Récupère la réalisation UA d'un apprenant pour une unité d'apprentissage donnée.
     * Si elle n'existe pas, elle est générée automatiquement via la réalisation de micro-compétence.
     *
     * @param  int $apprenantId
     * @param  int $uniteApprentissageId
     * @return RealisationUa
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getOrCreateApprenant(int $apprenantId, int $uniteApprentissageId): RealisationUa
    {
        // Vérifier si la réalisation UA existe déjà
        $realisationUa = $this->model
            ->where('unite_apprentissage_id', $uniteApprentissageId)
            ->whereHas('realisationMicroCompetence', fn($query) =>
                $query->where('apprenant_id', $apprenantId)
            )
            ->first();

        if ($realisationUa) {
            return $realisationUa;
        }

        // Identifier la micro-compétence liée à l'unité d'apprentissage
        $microCompetenceId = UniteApprentissage::findOrFail($uniteApprentissageId)
            ->micro_competence_id;

        // Forcer la création via la réalisation de micro-compétence
        (new RealisationMicroCompetenceService())
            ->getOrCreateByApprenant($apprenantId, $microCompetenceId);

        // Rechercher à nouveau la réalisation UA (elle est créée par afterCreateRules)
        return $this->model
            ->where('unite_apprentissage_id', $uniteApprentissageId)
            ->whereHas('realisationMicroCompetence', fn($query) =>
                $query->where('apprenant_id', $apprenantId)
            )
            ->firstOrFail();
    }


}
