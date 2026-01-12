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
        // Récupérer l'état initial (TODO) propre au formateur du projet
        $etatInitialId = null;
        if ($formateur_id) {
            $etatInitialId = \Modules\PkgRealisationTache\Models\EtatRealisationTache::where('formateur_id', $formateur_id)
                ->whereHas('workflowTache', function ($q) {
                    $q->where('code', 'TODO');
                })->value('id');
        }

        $realisationUaService = new RealisationUaService();
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

    /**
     * Crée les RealisationTache pour les tâches de type tutoriel (N1) associées à une mobilisation UA.
     * Vérifie si le chapitre est déjà validé pour ne pas créer de doublon inutile.
     *
     * @param RealisationProjet $realisationProjet
     * @param MobilisationUa $mobilisation
     * @return void
     */
    /**
     * @deprecated Cette méthode est obsolète car TacheService::afterCreateRules gère maintenant la création automatique.
     */
    public function createFormMobilisation(RealisationProjet $realisationProjet, MobilisationUa $mobilisation): void
    {
        // Méthode conservée vide pour éviter les erreurs fatales si appelée ailleurs,
        // mais le code a été déplacé vers TacheService::afterCreateRules.
    }




}
