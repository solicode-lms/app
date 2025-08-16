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
     * - Les tâches en état PAUSED sont exclues du total.
     * - Si aucune tâche active (≠ PAUSED) → 0%
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

        $etatCodePause = 'PAUSED';
        $etatCodesRealises = ['TO_APPROVE', 'APPROVED', 'READY_FOR_LIVE_CODING', 'IN_LIVE_CODING'];

        // 🔍 Tâches actives (non en pause)
        $tachesNonPause = $realisationTaches->filter(function ($tache) use ($etatCodePause) {
            return optional($tache->etatRealisationTache?->workflowTache)->code !== $etatCodePause;
        });

        if ($tachesNonPause->isEmpty()) {
            $tacheAffectation->update(['pourcentage_realisation_cache' => 0]);
            return;
        }

        $realisees = $tachesNonPause->filter(function ($tache) use ($etatCodesRealises) {
            return in_array(optional($tache->etatRealisationTache?->workflowTache)->code, $etatCodesRealises);
        })->count();

        $progression = round(($realisees / $tachesNonPause->count()) * 100, 2);

        $tacheAffectation->update(['pourcentage_realisation_cache' => $progression]);
    }


public function lancerLiveCodingSiEligible(TacheAffectation $tacheAffectation): void
{

    // ⛔ Ne rien faire si un live coding est déjà en cours pour cette tâche
    if ($tacheAffectation->realisationTaches()->where('is_live_coding', true)->exists()) {
        return;
    }

    if (($tacheAffectation->pourcentage_realisation_cache ?? 0) < 50) return;

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

    if ($tachesEligibles->isEmpty()) return;

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

    if (!$apprenantSelectionne_realisation_tache) return;

    // 🎯 Récupérer l’état "IN_LIVE_CODING"
    $formateurId = $tacheAffectation?->affectationProjet
        ?->projet
        ?->formateur_id;

    $etatLiveCoding = EtatRealisationTache::whereHas('workflowTache', fn($q) =>
            $q->where('code', 'READY_FOR_LIVE_CODING'))
        ->where('formateur_id', $formateurId)
        ->first();

    if (!$etatLiveCoding) return;

    // ✅ Mettre à jour l’état et is_live_coding
    $apprenantSelectionne_realisation_tache->update([
        'is_live_coding' => true,
        'etat_realisation_tache_id' => $etatLiveCoding->id,
    ]);
}



}
