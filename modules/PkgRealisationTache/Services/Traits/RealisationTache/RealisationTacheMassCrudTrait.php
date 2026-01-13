<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Services\EtatRealisationTacheService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgCreationProjet\Models\MobilisationUa;

trait RealisationTacheMassCrudTrait
{
    /**
     * Rappeler le processus de création des tâches depuis l'affectation.
     * Cette méthode centralise la logique de création initiale des tâches.
     * aprés l'affectation de projet à un groupe
     *
     * @param RealisationProjet $realisationProjet
     * @return void
     */
    public function createFromRealisationProjet(RealisationProjet $realisationProjet): void
    {
        // Récupérer l'état initial (TODO) propre au formateur du projet
        $formateur_id = $realisationProjet->affectationProjet->projet->formateur_id;
        $etatInitialId = null;
        if ($formateur_id) {
            $etatRealisationTacheService = new EtatRealisationTacheService();
            $etatInitial = $etatRealisationTacheService->getDefaultEtatByFormateurId($formateur_id);
            $etatInitialId = $etatInitial ? $etatInitial->id : null;
        }

        $tacheAffectations = $realisationProjet->affectationProjet->tacheAffectations;
        foreach ($tacheAffectations as $tacheAffectation) {
            // Unicité : on vérifie si la tâche est déjà réalisée
            $existeRT = $realisationProjet->realisationTaches()->where('tache_id', $tacheAffectation->tache->id)->exists();
            if ($existeRT) {
                continue;
            }

            // Création standard qui déclenchera beforeCreateRules
            $this->create([
                'realisation_projet_id' => $realisationProjet->id,
                'tache_id' => $tacheAffectation->tache->id,
                'etat_realisation_tache_id' => $etatInitialId,
                'tache_affectation_id' => $tacheAffectation->id
            ]);
        }
    }

}
