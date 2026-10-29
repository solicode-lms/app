<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Modules\PkgApprentissage\Models\RealisationChapitre;
use Modules\PkgApprentissage\Services\RealisationChapitreService;
use Modules\PkgApprentissage\Services\RealisationUaService;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\RealisationTacheService;

/**
 * Trait dédié à l'intégration des tâches avec les Chapitres et les Unités d'Apprentissage (UAs) :
 * - Vérification si un chapitre est déjà validé (DONE) pour l'apprenant.
 * - Validation automatique du dernier chapitre lorsque l'UA complète est validée.
 */
trait RealisationTacheChapitreUaTrait
{
    /**
     * Vérifie si le chapitre lié à la tâche est déjà validé (DONE) pour cet apprenant.
     *
     * @param array $data
     * @return bool
     */
    protected function shouldSkipCreationIfChapitreDone(array $data): bool
    {
        // Bypass si flag explicite (évite boucle infinie lors de création automatique)
        if (isset($data['__bypass_chapter_check']) && $data['__bypass_chapter_check'] === true) {
            return false;
        }

        if (empty($data['tache_id']) || empty($data['realisation_projet_id'])) {
            return false;
        }

        $tache = Tache::with('chapitre')->find($data['tache_id']);
        if (!$tache || !$tache->chapitre) {
            return false;
        }

        $realisationProjet = RealisationProjet::with('affectationProjet.projet')->find($data['realisation_projet_id']);
        if (!$realisationProjet) {
            return false;
        }

        // Vérification de l'état du chapitre pour cet apprenant
        $realisationUaService = new RealisationUaService();
        $realisationUA = $realisationUaService->getOrCreateApprenant(
            $realisationProjet->apprenant_id,
            $tache->chapitre->unite_apprentissage_id
        );

        $realisationChapitreService = app(RealisationChapitreService::class);
        return $realisationChapitreService->isChapitreAlreadyDone($tache->chapitre->id, $realisationUA->id);
    }

    /**
     * Vérifie si l'UA est terminée et crée la tâche de validation du dernier chapitre si nécessaire.
     *
     * @param int $tacheId
     * @param int $realisationProjetId
     * @return void
     */
    protected function checkAndPerformUaValidationLogic(int $tacheId, int $realisationProjetId): void
    {
        $tache = Tache::with('chapitre.uniteApprentissage.chapitres')->find($tacheId);
        $realisationProjet = RealisationProjet::with('apprenant')->find($realisationProjetId);

        if (!$tache || !$tache->chapitre || !$realisationProjet) {
            return;
        }

        $ua = $tache->chapitre->uniteApprentissage;
        if ($ua) {
            $totalChapitres = $ua->chapitres->count();

            $realisationUaService = new RealisationUaService();
            $realisationUA = $realisationUaService->getOrCreateApprenant(
                $realisationProjet->apprenant_id,
                $tache->chapitre->unite_apprentissage_id
            );

            // Nombre de chapitres validés pour cette UA et cet apprenant
            $chapitresValides = RealisationChapitre::where('realisation_ua_id', $realisationUA->id)
                ->whereHas('etatRealisationChapitre', function ($q) {
                    $q->where('code', 'DONE');
                })
                ->count();

            if ($chapitresValides >= $totalChapitres) {
                $etatApprovedId = EtatRealisationTache::whereHas('workflowTache', function ($q) {
                    $q->where('code', 'APPROVED');
                })->value('id');

                if ($etatApprovedId) {
                    $dernierChapitre = $ua->chapitres()->orderBy('ordre', 'desc')->first();

                    if ($dernierChapitre) {
                        $tacheDernierChapitre = Tache::where('chapitre_id', $dernierChapitre->id)->first();

                        if ($tacheDernierChapitre) {
                            $exists = RealisationTache::where('tache_id', $tacheDernierChapitre->id)
                                ->where('realisation_projet_id', $realisationProjet->id)
                                ->exists();

                            if (!$exists) {
                                $realisationTacheService = new RealisationTacheService();
                                $realisationTacheService->create([
                                    'tache_id' => $tacheDernierChapitre->id,
                                    'realisation_projet_id' => $realisationProjet->id,
                                    'etat_realisation_tache_id' => $etatApprovedId,
                                    'date_debut' => now(),
                                    'date_fin' => now(),
                                    'description' => "Validation automatique via UA Completed",
                                    '__bypass_chapter_check' => true
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
