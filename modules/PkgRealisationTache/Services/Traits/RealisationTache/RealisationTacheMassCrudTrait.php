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
     *
     * @param RealisationProjet $realisationProjet
     * @return void
     */
    public function createFromRealisationProjet(RealisationProjet $realisationProjet): void
    {
        $formateur_id = $realisationProjet->affectationProjet->projet->formateur_id;
        $etatInitialId = $formateur_id
            ? (new EtatRealisationTacheService())->getDefaultEtatByFormateurId($formateur_id)?->id
            : null;

        $realisationUaService = new RealisationUaService();
        $tacheAffectations = $realisationProjet->affectationProjet->tacheAffectations;

        foreach ($tacheAffectations as $tacheAffectation) {
            $this->createRealisationTacheIfEligible(
                $tacheAffectation->tache,
                $realisationProjet,
                $realisationUaService,
                $etatInitialId,
                $tacheAffectation->id
            );
        }
    }

    /**
     * Crée les RealisationTache pour les tâches de type tutoriel (N1) associées à une mobilisation UA.
     * Vérifie si le chapitre est déjà validé pour ne pas créer de doublon inutile.
     *
     * @param RealisationProjet $realisationProjet
     * @param MobilisationUa $mobilisation
     * @return void
     */
    public function createFormMobilisation(RealisationProjet $realisationProjet, MobilisationUa $mobilisation): void
    {
        // Récupérer les tâches N1 (Tutoriels) liées à cette UA pour ce projet
        $tachesN1 = Tache::where('projet_id', $mobilisation->projet_id)
            ->whereHas('chapitre', function ($q) use ($mobilisation) {
                $q->where('unite_apprentissage_id', $mobilisation->unite_apprentissage_id);
            })
            ->get();

        $realisationUaService = new RealisationUaService();

        foreach ($tachesN1 as $tache) {
            $this->createRealisationTacheIfEligible(
                $tache,
                $realisationProjet,
                $realisationUaService
            );
        }
    }


}
