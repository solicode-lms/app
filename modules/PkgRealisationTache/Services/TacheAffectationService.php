<?php


namespace Modules\PkgRealisationTache\Services;

use Modules\PkgFormation\Services\AnneeFormationService;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\TacheAffectation;
use Modules\PkgRealisationTache\Services\Base\BaseTacheAffectationService;

/**
 * Classe TacheAffectationService pour gérer la persistance de l'entité TacheAffectation.
 */
class TacheAffectationService extends BaseTacheAffectationService
{
    /**
     * Met à jour le champ `pourcentage_realisation_cache` d’une TacheAffectation,
     * en fonction des tâches réellement réalisées par les apprenants.
     *
     * 🎯 Règle métier :
     * - États considérés comme "réalisés" : TO_APPROVE, APPROVED, READY_FOR_LIVE_CODING, IN_LIVE_CODING
     * - Les tâches en état PAUSED ou NOT_VALIDATED sont exclues du total.
     * - Si aucune tâche active (≠ PAUSED, NOT_VALIDATED) → 0%
     * - Calcul : (nombre de tâches réalisées / total des tâches non en pause) × 100
     *
     * @param TacheAffectation $tacheAffectation L’entité à mettre à jour
     * @return void
     */
    public function mettreAjourTacheProgression(TacheAffectation $tacheAffectation): void
    {
        $realisationTaches = $tacheAffectation->realisationTaches;

        if ($realisationTaches->isEmpty()) {
            $tacheAffectation->update(['pourcentage_realisation_cache' => 0]);
            return;
        }

        $etatsExclus = ['PAUSED', 'NOT_VALIDATED'];
        $etatCodesRealises = ['TO_APPROVE', 'APPROVED', 'READY_FOR_LIVE_CODING', 'IN_LIVE_CODING'];

        // 🔍 Tâches actives (non exclues)
        $tachesActives = $realisationTaches->filter(function ($tache) use ($etatsExclus) {
            return !in_array(optional($tache->etatRealisationTache?->workflowTache)->code, $etatsExclus);
        });

        if ($tachesActives->isEmpty()) {
            $tacheAffectation->update(['pourcentage_realisation_cache' => 0]);
            return;
        }

        $realisees = $tachesActives->filter(function ($tache) use ($etatCodesRealises) {
            return in_array(optional($tache->etatRealisationTache?->workflowTache)->code, $etatCodesRealises);
        })->count();

        $progression = round(($realisees / $tachesActives->count()) * 100, 2);

        $tacheAffectation->update(['pourcentage_realisation_cache' => $progression]);
    }


    public function lancerLiveCodingSiEligible(TacheAffectation $tacheAffectation): void
    {


        // 🚫 Ne rien faire si la tâche n’est pas prévue pour le live coding
        if (!$tacheAffectation->tache?->is_live_coding_task) {
            return;
        }


        // ⛔ Ne rien faire si un live coding est déjà en cours pour cette tâche
        if ($tacheAffectation->realisationTaches()->where('is_live_coding', true)->exists()) {
            return;
        }



        if (($tacheAffectation->pourcentage_realisation_cache ?? 0) < 50)
            return;

        $codesValides = ['TO_APPROVE'];

        $tachesEligibles = $tacheAffectation->realisationTaches()
            ->with([
                'etatRealisationTache.workflowTache',
                'realisationProjet.apprenant',
                'tacheAffectation.affectationProjet.projet.formateur'
            ])
            ->get()
            ->filter(function ($tache) use ($codesValides) {
                return in_array(optional($tache->etatRealisationTache?->workflowTache)->code, $codesValides)
                    && !$tache->is_live_coding
                    && $tache->realisationProjet?->apprenant_id; // sécurité
            });

        if ($tachesEligibles->isEmpty())
            return;

        // 📅 Année scolaire en cours
        $anneeDebut = (new AnneeFormationService())
            ->getCurrentAnneeFormation()
                ?->date_debut ?? now()->startOfYear();

        // 🔁 Tri par nombre de live coding faits par apprenant cette année
        $apprenantSelectionne_realisation_tache = $tachesEligibles->sortBy(function ($tache) use ($anneeDebut) {
            $apprenantId = $tache->realisationProjet->apprenant_id;

            return RealisationTache::whereHas('realisationProjet', fn($q) =>
                $q->where('apprenant_id', $apprenantId))
                ->where('is_live_coding', true)
                ->where('updated_at', '>=', $anneeDebut)
                ->count();
        })->first();

        if (!$apprenantSelectionne_realisation_tache)
            return;

        // 🎯 Récupérer l’état "IN_LIVE_CODING"
        $formateurId = $tacheAffectation?->affectationProjet
            ?->projet
                ?->formateur_id;

        $etatLiveCoding = EtatRealisationTache::whereHas('workflowTache', fn($q) =>
            $q->where('code', 'READY_FOR_LIVE_CODING'))
            ->where('formateur_id', $formateurId)
            ->first();

        if (!$etatLiveCoding)
            return;

        // ✅ Mettre à jour l’état et is_live_coding
        $apprenantSelectionne_realisation_tache->update([
            'is_live_coding' => true,
            'etat_realisation_tache_id' => $etatLiveCoding->id,
        ]);

        // ✅ Sauvegarder l’apprenant dans le champ JSON
        $tacheAffectation->update([
            'apprenant_live_coding_cache' => [
                'apprenant' => (string) $apprenantSelectionne_realisation_tache->realisationProjet->apprenant,
                'realisation_tache_id' => $apprenantSelectionne_realisation_tache->id,
                'date' => now()->toDateTimeString(),
            ]
        ]);
    }


    /**
     * Récupère ou crée une TacheAffectation.
     *
     * @param \Modules\PkgCreationTache\Models\Tache $tache
     * @param \Modules\PkgRealisationProjets\Models\AffectationProjet $affectationProjet
     * @return TacheAffectation
     */
    public function getOrCreateTacheAffectation(\Modules\PkgCreationTache\Models\Tache $tache, \Modules\PkgRealisationProjets\Models\AffectationProjet $affectationProjet): TacheAffectation
    {
        $tacheAffectation = $this->model->where('tache_id', $tache->id)
            ->where('affectation_projet_id', $affectationProjet->id)
            ->first();

        if (!$tacheAffectation) {
            $tacheAffectation = $this->create([
                'tache_id' => $tache->id,
                'affectation_projet_id' => $affectationProjet->id,
                'date_debut' => $tache->dateDebut ?? now(),
                'date_fin' => $tache->dateFin ?? now()->addWeek(),
            ]);
        }

        return $tacheAffectation;
    }

}
