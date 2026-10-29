<?php

namespace Modules\PkgRealisationTache\Services\Traits\RealisationTache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;

/**
 * Trait dédié à la gestion des règles métier relatives à l'absence des apprenants :
 * - Sécurisation des transitions (interdiction pour un apprenant de se marquer absent).
 * - Reprise de travail depuis l'état ABSENT (avec réorientation éventuelle en remédiation).
 * - Propagation automatique de l'état ABSENT sur la tâche suivante.
 */
trait RealisationTacheAbsenceTrait
{
    /**
     * Valide les règles d'absence avant la mise à jour d'une tâche.
     *
     * @param RealisationTache $realisationTache
     * @param array $data
     * @param bool $isFormateur
     * @return bool True si une redirection automatique vers la remédiation a eu lieu
     * @throws ValidationException
     */
    protected function gererReglesAbsenceAvantUpdate(RealisationTache $realisationTache, array &$data, bool $isFormateur): bool
    {
        if (empty($data['etat_realisation_tache_id'])) {
            return false;
        }

        $etatDemande = EtatRealisationTache::find($data['etat_realisation_tache_id']);
        $codeEtatDemande = optional($etatDemande?->workflowTache)->code;

        // 1. Interdiction formelle : un apprenant ne peut jamais marquer une tâche comme ABSENT
        if (!$isFormateur && $codeEtatDemande === 'ABSENT') {
            throw ValidationException::withMessages([
                'etat_realisation_tache_id' => __('Seul un formateur peut marquer une tâche comme absente.')
            ]);
        }

        // 2. Transition depuis ABSENT vers IN_PROGRESS par un apprenant
        $etatActuelCode = optional($realisationTache->etatRealisationTache?->workflowTache)->code;
        $isTransitionDepuisAbsent = ($etatActuelCode === 'ABSENT');
        $isAutoRedirectionVersRemediation = false;

        if (!$isFormateur && $isTransitionDepuisAbsent && $codeEtatDemande === 'IN_PROGRESS') {
            // Vérifier si la tâche était en remédiation avant l'absence via le trait dédié
            if (method_exists($this, 'verifierSiTacheEnRemediationAvantAbsence') && $this->verifierSiTacheEnRemediationAvantAbsence($realisationTache)) {
                $formateurId = $realisationTache->realisationProjet?->affectationProjet?->projet?->formateur_id;
                $etatRemediation = EtatRealisationTache::whereHas('workflowTache', fn($q) =>
                    $q->where('code', 'EN_REMEDIATION')
                )->where(function ($q) use ($formateurId) {
                    if ($formateurId) {
                        $q->where('formateur_id', $formateurId)->orWhereNull('formateur_id');
                    } else {
                        $q->whereNull('formateur_id');
                    }
                })->orderByRaw('formateur_id IS NULL ASC')->first();

                if ($etatRemediation) {
                    $data['etat_realisation_tache_id'] = $etatRemediation->id;
                    $isAutoRedirectionVersRemediation = true;
                }
            }
        }

        return $isAutoRedirectionVersRemediation;
    }

    /**
     * Propage l'état ABSENT sur la tâche actuelle pour les apprenants absents de la même affectation
     * lorsqu'un pair commence la tâche suivante (état IN_PROGRESS).
     *
     * @param RealisationTache $realisationTache
     * @return void
     */
    public function propagerEtatAbsentPourTacheSuivante(RealisationTache $realisationTache): void
    {
        $tacheActuelle = $realisationTache->tache;
        $affectation = $realisationTache->realisationProjet?->affectationProjet;

        if (!$tacheActuelle || !$affectation) {
            return;
        }

        // 1. Déterminer la séquence ordonnée des tâches du projet
        $tachesDuProjet = Tache::where('projet_id', $tacheActuelle->projet_id)
            ->orderBy('priorite')
            ->orderBy('ordre')
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $currentIndex = array_search($tacheActuelle->id, $tachesDuProjet);
        if ($currentIndex === false || $currentIndex === 0) {
            return;
        }

        $tachesPrecedentesIds = array_slice($tachesDuProjet, 0, $currentIndex);

        // 2. Trouver l'état ABSENT pour le formateur du projet (ou état système)
        $formateurId = $affectation->projet?->formateur_id;
        $etatAbsent = EtatRealisationTache::whereHas('workflowTache', fn($q) =>
            $q->where('code', 'ABSENT')
        )->where(function ($q) use ($formateurId) {
            if ($formateurId) {
                $q->where('formateur_id', $formateurId)->orWhereNull('formateur_id');
            } else {
                $q->whereNull('formateur_id');
            }
        })->orderByRaw('formateur_id IS NULL ASC')->first();

        if (!$etatAbsent) {
            return;
        }

        // 3. Identifier les autres apprenants de la même affectation
        $autresRealisationsProjet = RealisationProjet::where('affectation_projet_id', $affectation->id)
            ->where('id', '!=', $realisationTache->realisation_projet_id)
            ->get();

        foreach ($autresRealisationsProjet as $rp) {
            $rtsPrecedentes = RealisationTache::where('realisation_projet_id', $rp->id)
                ->whereIn('tache_id', $tachesPrecedentesIds)
                ->get()
                ->keyBy('tache_id');

            $estAbsentSurPrecedente = false;
            for ($i = count($tachesPrecedentesIds) - 1; $i >= 0; $i--) {
                $tId = $tachesPrecedentesIds[$i];
                $rtPrec = $rtsPrecedentes->get($tId);
                if ($rtPrec) {
                    $wfCodePrec = optional($rtPrec->etatRealisationTache?->workflowTache)->code;
                    if ($wfCodePrec === 'ABSENT') {
                        $estAbsentSurPrecedente = true;
                        break;
                    } elseif (in_array($wfCodePrec, ['APPROVED', 'VALIDEE', 'VALIDE_INTERMEDIAIRE', 'IN_PROGRESS', 'TO_APPROVE'])) {
                        break;
                    }
                }
            }

            if ($estAbsentSurPrecedente) {
                $rtActuelle = RealisationTache::where('realisation_projet_id', $rp->id)
                    ->where('tache_id', $tacheActuelle->id)
                    ->first();

                if ($rtActuelle) {
                    $codeActuel = optional($rtActuelle->etatRealisationTache?->workflowTache)->code;
                    if ($codeActuel !== 'ABSENT' && !in_array($codeActuel, ['APPROVED', 'VALIDEE', 'VALIDE_INTERMEDIAIRE', 'NOT_VALIDATED'])) {
                        $rtActuelle->update([
                            'etat_realisation_tache_id' => $etatAbsent->id,
                        ]);

                        HistoriqueRealisationTache::create([
                            'realisation_tache_id' => $rtActuelle->id,
                            'dateModification' => now(),
                            'changement' => 'État : Absent (Propagation automatique absence apprenant)',
                            'user_id' => Auth::id() ?? $affectation->projet?->formateur?->user_id,
                            'isFeedback' => false,
                        ]);
                    }
                }
            }
        }
    }
}
