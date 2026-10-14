<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgTableauBord\Models\Remediation;

trait DashboardIndicateur06Trait
{
    /**
     * Indicateur 06 — Suivi d'accompagnement didactique pour l'Apprenant.
     */
    public function getIndicateurInterventionApprenant(?RealisationProjet $realisationProjet, ?User $user = null): array
    {
        if (!$realisationProjet) {
            return [
                'besoin_aide' => false,
                'message' => null,
            ];
        }

        $affectation = $realisationProjet->affectationProjet;
        $seuilMinutes = (int) ($affectation->seuil_blocage_minutes ?? 10);
        $dateFin = $affectation->date_fin_live_coding_collectif ?? null;

        if (!$dateFin) {
            return [
                'besoin_aide' => false,
                'message' => null,
            ];
        }

        $echeance = Carbon::parse($dateFin)->addMinutes($seuilMinutes);
        if (now()->lessThanOrEqualTo($echeance)) {
            return [
                'besoin_aide' => false,
                'message' => null,
            ];
        }

        // Vérifier si l'apprenant a une tâche de tutoriel non encore soumise
        $tacheEnCours = RealisationTache::where('realisation_projet_id', $realisationProjet->id)
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->whereIn('code', ['TODO', 'IN_PROGRESS', 'BESOIN_REVISION']);
            })
            ->first();

        if (!$tacheEnCours) {
            return [
                'besoin_aide' => false,
                'message' => null,
            ];
        }

        // Vérifier si une remédiation a déjà été prise en charge
        $hasTable = Schema::hasTable('remediations');
        $hasRemediation = $hasTable && DB::table('remediations')
            ->where('realisation_tache_id', $tacheEnCours->id)
            ->exists();

        if ($hasRemediation) {
            return [
                'besoin_aide' => false,
                'message' => 'Une intervention de déblocage avec le formateur est enregistrée pour votre tâche.',
            ];
        }

        $minutesDepassement = max(1, (int) Carbon::parse($echeance)->diffInMinutes(now()));

        return [
            'besoin_aide' => true,
            'seuil_minutes' => $seuilMinutes,
            'minutes_depassement' => $minutesDepassement,
            'tache_titre' => $tacheEnCours->tache->titre ?? 'Tutoriel Niveau 1',
            'message' => "Le Live Coding collectif est terminé depuis {$seuilMinutes} minutes. Besoin d'aide sur votre code ? Le formateur et vos pairs sont mobilisés pour vous accompagner.",
        ];
    }

    /**
     * Indicateur 06 — Prise en charge des blocages & Flashbox Alerte Seuil 10 min pour le Formateur.
     */
    public function getIndicateurInterventionFormateur(?AffectationProjet $affectationProjet, ?Formateur $formateur = null): array
    {
        if (!$affectationProjet) {
            return [
                'taux_intervention' => 100.0,
                'taux_intervention_formate' => '100%',
                'en_attente_soutien' => 0,
                'total_bloques' => 0,
                'total_remedies' => 0,
                'seuil_minutes' => 10,
                'zone' => 'excellence',
                'badge_label' => 'Excellence (>= 85%)',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'afficher_flashbox' => false,
                'apprenants_en_attente' => [],
            ];
        }

        // Seuil paramétrable lors de l'affectation du projet (par défaut 10 min)
        $seuilMinutes = (int) ($affectationProjet->seuil_blocage_minutes ?? 10);
        $dateFinLiveCoding = $affectationProjet->date_fin_live_coding_collectif;

        $hasTableRemediations = Schema::hasTable('remediations');

        $apprenantsBloques = [];
        $apprenantsRemedies = [];

        if ($dateFinLiveCoding) {
            $echeanceSeuil = Carbon::parse($dateFinLiveCoding)->addMinutes($seuilMinutes);
            $maintenant = now();

            $realisationsProjet = RealisationProjet::where('affectation_projet_id', $affectationProjet->id)
                ->with(['apprenant.user', 'realisationTaches.tache', 'realisationTaches.etatRealisationTache.workflowTache'])
                ->get();

            foreach ($realisationsProjet as $rp) {
                $tacheBloquee = $rp->realisationTaches->first(function ($rt) {
                    $code = optional($rt->etatRealisationTache?->workflowTache)->code;
                    return in_array($code, ['TODO', 'IN_PROGRESS', 'BESOIN_REVISION']);
                });

                if ($tacheBloquee) {
                    $dateEntreeBlocage = $tacheBloquee->updated_at ?? $dateFinLiveCoding;
                    $diffMinutes = max(1, (int) Carbon::parse($echeanceSeuil)->diffInMinutes($maintenant));

                    $aRecuRemediation = $hasTableRemediations && DB::table('remediations')
                        ->where('realisation_tache_id', $tacheBloquee->id)
                        ->exists();

                    $apprenant = $rp->apprenant;
                    $nomComplet = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? ''));

                    if ($aRecuRemediation) {
                        $apprenantsRemedies[] = [
                            'apprenant_id' => $apprenant?->id,
                            'nom' => $nomComplet,
                            'tache_id' => $tacheBloquee->id,
                        ];
                    } else {
                        // Toujours en attente d'aide post-seuil
                        $apprenantsBloques[] = [
                            'apprenant_id' => $apprenant?->id,
                            'nom' => $nomComplet,
                            'nom_complet' => $nomComplet,
                            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet ?: 'A') . '&background=fee2e2&color=991b1b',
                            'depassement_formate' => '+' . $diffMinutes . ' min',
                            'tache_id' => $tacheBloquee->id,
                            'tache_titre' => $tacheBloquee->tache->titre ?? 'Tutoriel',
                        ];
                    }
                }
            }
        }

        $totalBloques = count($apprenantsBloques) + count($apprenantsRemedies);
        $totalRemedies = count($apprenantsRemedies);

        if ($totalBloques === 0) {
            $taux = 100.0;
        } else {
            $taux = min(100.0, round(($totalRemedies / $totalBloques) * 100, 1));
        }

        // Seuils didactiques : Excellence (>= 85%), Vigilance (60-84%), Rupture (< 60%)
        if ($taux >= 85.0) {
            $zone = 'excellence';
            $badgeLabel = 'Excellence (>= 85%)';
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($taux >= 60.0) {
            $zone = 'vigilance';
            $badgeLabel = 'Charge active';
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zone = 'alerte';
            $badgeLabel = 'Déficit d\'accompagnement';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        return [
            'affectation_id' => $affectationProjet->id,
            'seuil_minutes' => $seuilMinutes,
            'taux_intervention' => $taux,
            'taux_intervention_formate' => $taux . '%',
            'total_bloques' => $totalBloques,
            'total_remedies' => $totalRemedies,
            'en_attente_soutien' => count($apprenantsBloques),
            'zone' => $zone,
            'badge_label' => $badgeLabel,
            'badge_class' => $badgeClass,
            'afficher_flashbox' => count($apprenantsBloques) > 0,
            'apprenants_en_attente' => $apprenantsBloques,
        ];
    }

    /**
     * Indicateur 06 — Gouvernance macroscopique Responsable.
     */
    public function getIndicateurInterventionResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        $queryAff = AffectationProjet::with(['groupe', 'projet']);

        if (!empty($groupeIds)) {
            $queryAff->whereIn('groupe_id', $groupeIds);
        }
        if ($projetId) {
            $queryAff->where('projet_id', $projetId);
        }

        $affectations = $queryAff->get();

        $benchmark = [];
        $totalTaux = 0;
        $nbValides = 0;

        foreach ($affectations as $aff) {
            $kpi = $this->getIndicateurInterventionFormateur($aff);
            $totalTaux += $kpi['taux_intervention'];
            $nbValides++;

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? '',
                'projet_titre' => $aff->projet->titre ?? '',
                'taux_intervention' => $kpi['taux_intervention'],
                'taux_intervention_formate' => $kpi['taux_intervention_formate'],
                'en_attente_soutien' => $kpi['en_attente_soutien'],
                'zone' => $kpi['zone'],
                'badge_label' => $kpi['badge_label'],
                'badge_class' => $kpi['badge_class'],
            ];
        }

        $moyenneGlobale = $nbValides > 0 ? round($totalTaux / $nbValides, 1) : 100.0;

        return [
            'taux_moyen_global' => $moyenneGlobale,
            'taux_moyen_global_formate' => $moyenneGlobale . '%',
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Enregistre une intervention d'étayage didactique (Indicateur 06).
     */
    public function enregistrerRemediation(int $realisationTacheId, int $formateurId, string $type = 'INDIVIDUELLE', ?string $diagnostic = null): ?Remediation
    {
        if (!Schema::hasTable('remediations')) {
            return null;
        }

        $reference = 'REM_' . $realisationTacheId . '_' . date('Ymd') . '_' . uniqid();

        $id = DB::table('remediations')->insertGetId([
            'realisation_tache_id' => $realisationTacheId,
            'formateur_id' => $formateurId,
            'type_remediation' => $type,
            'diagnostic' => $diagnostic,
            'est_resolu' => true,
            'reference' => $reference,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Remediation::find($id);
    }
}
