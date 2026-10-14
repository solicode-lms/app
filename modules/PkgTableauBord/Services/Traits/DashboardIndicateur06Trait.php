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
        $seuilMinutes = (int) ($affectation->seuil_blocage_minutes ?? 30);
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
            'message' => "Le Live Coding collectif est terminé depuis {$minutesDepassement} minutes au-delà du seuil d'autonomie ({$seuilMinutes} min). Besoin d'aide sur votre code ? Le formateur et vos pairs sont mobilisés pour vous accompagner.",
        ];
    }

    /**
     * Indicateur 06 — Prise en charge des blocages & Flashbox Alerte Seuil 30 min pour le Formateur.
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
                'seuil_minutes' => 30,
                'zone' => 'excellence',
                'badge_label' => 'Excellence (>= 85%)',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'afficher_flashbox' => false,
                'apprenants_en_attente' => [],
            ];
        }

        // Seuil paramétrable lors de l'affectation du projet (par défaut 30 min)
        $seuilMinutes = (int) ($affectationProjet->seuil_blocage_minutes ?? 30);
        $dateFinLiveCoding = $this->determinerDateFinLiveCodingCollectif($affectationProjet);
        $this->synchroniserApprenantsEnRemediation($affectationProjet, $seuilMinutes);

        $realisationsProjet = RealisationProjet::where('affectation_projet_id', $affectationProjet->id)
            ->with(['apprenant.user', 'realisationTaches.tache', 'realisationTaches.etatRealisationTache.workflowTache'])
            ->get();

        $totalApprenants = $realisationsProjet->count();
        $apprenantsEnRemediation = [];

        // RÈGLE STRICTE : Récupérer les tâches éligibles ayant un démonstrateur (is_live_coding = 1) terminé ("A_VALIDER_LIVE_CODING" ou "APPROVED"/"VALIDEE")
        $tachesEligibles = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjet->id))
            ->where('is_live_coding', 1)
            ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->whereIn('code', ['A_VALIDER_LIVE_CODING', 'APPROVED', 'VALIDEE']))
            ->pluck('tache_id')
            ->unique()
            ->toArray();

        if (!empty($tachesEligibles)) {
            $maintenant = now();

            foreach ($realisationsProjet as $rp) {
                // Tâche actuellement en état de remédiation ET dont la tâche a fait l'objet d'un Live Coding valide
                $tacheRemediation = $rp->realisationTaches->first(function ($rt) use ($tachesEligibles) {
                    return optional($rt->etatRealisationTache?->workflowTache)->code === 'EN_REMEDIATION'
                        && in_array($rt->tache_id, $tachesEligibles);
                });

                if ($tacheRemediation) {
                    $demoTache = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjet->id))
                        ->where('tache_id', $tacheRemediation->tache_id)
                        ->where('is_live_coding', 1)
                        ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->whereIn('code', ['A_VALIDER_LIVE_CODING', 'APPROVED', 'VALIDEE']))
                        ->first();

                    $dateFinTache = $demoTache ? $this->determinerDateFinLiveCodingTache($demoTache) : $dateFinLiveCoding;
                    $echeanceSeuil = $dateFinTache ? Carbon::parse($dateFinTache)->addMinutes($seuilMinutes) : null;
                    $diffMinutes = ($echeanceSeuil && $maintenant->greaterThan($echeanceSeuil))
                        ? max(1, (int) Carbon::parse($echeanceSeuil)->diffInMinutes($maintenant))
                        : 0;

                    $apprenant = $rp->apprenant;
                    $nomComplet = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? ''));

                    $apprenantsEnRemediation[] = [
                        'apprenant_id' => $apprenant?->id,
                        'nom' => $nomComplet,
                        'nom_complet' => $nomComplet,
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet ?: 'A') . '&background=fee2e2&color=991b1b',
                        'depassement_formate' => $diffMinutes > 0 ? ('+' . $diffMinutes . ' min') : 'En remédiation',
                        'tache_id' => $tacheRemediation->id,
                        'tache_titre' => $tacheRemediation->tache->titre ?? 'Tutoriel',
                        'wf_code' => 'EN_REMEDIATION',
                        'wf_label' => $tacheRemediation->etatRealisationTache?->nom ?? 'En remédiation',
                    ];
                }
            }
        }

        // Récupérer toutes les remédiations enregistrées pour cette affectation
        $remediationsQuery = DB::table('remediations')
            ->join('realisation_taches', 'realisation_taches.id', '=', 'remediations.realisation_tache_id')
            ->join('realisation_projets', 'realisation_projets.id', '=', 'realisation_taches.realisation_projet_id')
            ->where('realisation_projets.affectation_projet_id', $affectationProjet->id);

        if (!empty($tachesEligibles)) {
            $remediationsQuery->whereIn('realisation_taches.tache_id', $tachesEligibles);
        } else {
            $remediationsQuery->whereRaw('1 = 0');
        }

        $allRemediations = Schema::hasTable('remediations')
            ? $remediationsQuery->select('remediations.*', 'realisation_projets.apprenant_id', 'realisation_taches.tache_id')->get()
            : collect();

        $apprenantIdsRemedies = $allRemediations->pluck('apprenant_id')->unique()->values()->toArray();
        $totalApprenantsAyantEuRemediation = count($apprenantIdsRemedies);
        $totalAutonomes = max(0, $totalApprenants - $totalApprenantsAyantEuRemediation);

        $nbEnRemediationActive = count($apprenantsEnRemediation);
        $totalResolus = $allRemediations->where('est_resolu', 1)->pluck('apprenant_id')->unique()->count();

        // 1. Taux d'Autonomie Initiale : (N_autonomes / N_total) * 100
        $tauxAutonomie = $totalApprenants > 0 ? round(($totalAutonomes / $totalApprenants) * 100, 1) : 100.0;

        // 2. Taux de Résolution des Remédiations : (N_résolus / N_remédiés) * 100
        $tauxResolution = $totalApprenantsAyantEuRemediation > 0 ? round(($totalResolus / $totalApprenantsAyantEuRemediation) * 100, 1) : 100.0;

        // Seuils didactiques basés sur l'état des blocages actifs
        if ($nbEnRemediationActive === 0) {
            $zone = 'excellence';
            $badgeLabel = 'Excellence (0 remédiation active)';
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($tauxAutonomie >= 60.0) {
            $zone = 'vigilance';
            $badgeLabel = $nbEnRemediationActive . ' en remédiation';
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zone = 'alerte';
            $badgeLabel = $nbEnRemediationActive . ' en remédiation';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        return [
            'affectation_id' => $affectationProjet->id,
            'seuil_minutes' => $seuilMinutes,
            'taux_intervention' => $tauxAutonomie,
            'taux_intervention_formate' => $tauxAutonomie . '%',
            'taux_autonomie' => $tauxAutonomie,
            'taux_autonomie_formate' => $tauxAutonomie . '%',
            'taux_resolution' => $tauxResolution,
            'taux_resolution_formate' => $tauxResolution . '%',
            'total_bloques' => $nbEnRemediationActive,
            'total_remedies' => $totalApprenantsAyantEuRemediation,
            'total_autonomes' => $totalAutonomes,
            'total_resolus' => $totalResolus,
            'en_attente_soutien' => $nbEnRemediationActive,
            'total_apprenants' => $totalApprenants,
            'zone' => $zone,
            'badge_label' => $badgeLabel,
            'badge_class' => $badgeClass,
            'afficher_flashbox' => $nbEnRemediationActive > 0,
            'apprenants_en_attente' => $apprenantsEnRemediation,
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
     * Détermine la date de fin réelle d'un Live Coding pour une tâche démonstrateur (transition vers "À valider Live coding" ou "Validée").
     */
    public function determinerDateFinLiveCodingTache(RealisationTache $demo): Carbon
    {
        $hist = DB::table('historique_realisation_taches')
            ->where('realisation_tache_id', $demo->id)
            ->where(function ($q) {
                $q->where('changement', 'LIKE', '%À valider Live coding%')
                  ->orWhere('changement', 'LIKE', '%Validée%')
                  ->orWhere('changement', 'LIKE', '%A_VALIDER_LIVE_CODING%');
            })
            ->orderByDesc('dateModification')
            ->first();

        if ($hist && !empty($hist->dateModification)) {
            return Carbon::parse($hist->dateModification);
        }

        return $demo->updated_at ? Carbon::parse($demo->updated_at) : now();
    }

    /**
     * Détermine la date de fin réelle du Live Coding collectif de l'affectation à partir du démonstrateur (is_live_coding = true).
     */
    public function determinerDateFinLiveCodingCollectif(AffectationProjet $affectation): ?Carbon
    {
        $demo = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectation->id))
            ->where('is_live_coding', 1)
            ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->whereIn('code', ['A_VALIDER_LIVE_CODING', 'APPROVED', 'VALIDEE']))
            ->latest('updated_at')
            ->first();

        if ($demo) {
            $date = $this->determinerDateFinLiveCodingTache($demo);
            if (empty($affectation->date_fin_live_coding_collectif)) {
                DB::table('affectation_projets')
                    ->where('id', $affectation->id)
                    ->update(['date_fin_live_coding_collectif' => $date]);
                $affectation->date_fin_live_coding_collectif = $date;
            }
            return $date;
        }

        return null;
    }

    /**
     * Bascule automatiquement les tâches des apprenants retardataires (> 30 min post-Live Coding) vers l'état "En remédiation".
     * RÈGLE CRITIQUE : Seules les tâches ayant eu une démonstration (is_live_coding = true) passée à "A_VALIDER_LIVE_CODING" ou "APPROVED"
     * sont éligibles. Si aucune réalisation n'a is_live_coding = true, aucune tâche n'est mise en remédiation.
     */
    public function synchroniserApprenantsEnRemediation(AffectationProjet $affectation, int $seuilMinutes = 30): array
    {
        $formateurId = $affectation->projet->formateur_id ?? 1;
        $etatRemediation = DB::table('etat_realisation_taches')
            ->join('workflow_taches', 'workflow_taches.id', '=', 'etat_realisation_taches.workflow_tache_id')
            ->where('workflow_taches.code', 'EN_REMEDIATION')
            ->where(function ($q) use ($formateurId) {
                $q->where('etat_realisation_taches.formateur_id', $formateurId)
                  ->orWhereNull('etat_realisation_taches.formateur_id');
            })
            ->select('etat_realisation_taches.id')
            ->first();

        if (!$etatRemediation) {
            return [];
        }

        // Charger toutes les réalisations de tâches de l'affectation
        $realisationsProjet = RealisationProjet::where('affectation_projet_id', $affectation->id)
            ->with(['realisationTaches.etatRealisationTache.workflowTache', 'realisationTaches.tache', 'apprenant'])
            ->get();

        $allRts = $realisationsProjet->flatMap->realisationTaches;
        $groupedByTache = $allRts->groupBy('tache_id');

        $apprenantsBasculees = [];

        foreach ($groupedByTache as $tacheId => $tasks) {
            // 1. RÈGLE STRICTE : Il doit exister au moins une réalisation avec is_live_coding = true
            $demos = $tasks->where('is_live_coding', 1);
            if ($demos->isEmpty()) {
                // Aucun apprenant n'a fait de Live Coding sur cette tâche -> Ne jamais mettre en remédiation
                // Nettoyage de sécurité : si une tâche a été mise en remédiation par erreur sans live coding, on la rétablit à TODO
                $invalides = $tasks->filter(fn($t) => optional($t->etatRealisationTache?->workflowTache)->code === 'EN_REMEDIATION');
                if ($invalides->isNotEmpty()) {
                    $etatTodoId = DB::table('etat_realisation_taches')
                        ->join('workflow_taches', 'workflow_taches.id', '=', 'etat_realisation_taches.workflow_tache_id')
                        ->where('workflow_taches.code', 'TODO')
                        ->where(function ($q) use ($formateurId) {
                            $q->where('etat_realisation_taches.formateur_id', $formateurId)
                              ->orWhereNull('etat_realisation_taches.formateur_id');
                        })
                        ->value('etat_realisation_taches.id');
                    if ($etatTodoId) {
                        DB::table('realisation_taches')->whereIn('id', $invalides->pluck('id'))->update(['etat_realisation_tache_id' => $etatTodoId]);
                    }
                }
                continue;
            }

            // 2. RÈGLE STRICTE : L'apprenant qui a fait le Live Coding doit être à "A_VALIDER_LIVE_CODING" ou "APPROVED" / "VALIDEE"
            $demoTerminee = $demos->first(function ($d) {
                $code = optional($d->etatRealisationTache?->workflowTache)->code;
                return in_array($code, ['A_VALIDER_LIVE_CODING', 'APPROVED', 'VALIDEE']);
            });

            if (!$demoTerminee) {
                // Le Live Coding est encore en cours ou non finalisé par le démonstrateur
                continue;
            }

            // 3. Déterminer la date de fin du Live Coding et vérifier le seuil de 30 min
            $dateFinLC = $this->determinerDateFinLiveCodingTache($demoTerminee);
            $echeanceSeuil = Carbon::parse($dateFinLC)->addMinutes($seuilMinutes);

            if (now()->lessThanOrEqualTo($echeanceSeuil)) {
                // Encore dans la fenêtre d'autonomie
                continue;
            }

            // 4. Identifier les autres apprenants (is_live_coding = 0) non terminés pour cette tâche
            // RÈGLE STRICTE : Ne JAMAIS mettre une tâche à l'état "Non validée" (NOT_VALIDATED) en remédiation
            $autresNonTermines = $tasks->filter(function ($t) {
                if ($t->is_live_coding) return false;
                $code = optional($t->etatRealisationTache?->workflowTache)->code;
                return in_array($code, ['TODO', 'IN_PROGRESS', 'REVISION_NECESSAIRE', 'PAUSED']);
            });

            foreach ($autresNonTermines as $rt) {
                DB::table('realisation_taches')
                    ->where('id', $rt->id)
                    ->update([
                        'etat_realisation_tache_id' => $etatRemediation->id,
                        'updated_at' => now(),
                    ]);

                DB::table('historique_realisation_taches')->insert([
                    'dateModification' => now(),
                    'changement' => 'État : En remédiation (Automatique post-Live Coding > ' . $seuilMinutes . ' min)',
                    'reference' => 'HIST_AUTO_REM_' . $rt->id . '_' . uniqid(),
                    'user_id' => $affectation->projet->formateur?->user_id ?? 1,
                    'isFeedback' => 0,
                    'realisation_tache_id' => $rt->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Traçabilité pérenne dans la table 'remediations' (est_resolu = 0)
                if (Schema::hasTable('remediations')) {
                    $existsRem = DB::table('remediations')->where('realisation_tache_id', $rt->id)->exists();
                    if (!$existsRem) {
                        DB::table('remediations')->insert([
                            'realisation_tache_id' => $rt->id,
                            'formateur_id' => $affectation->projet->formateur_id ?? 1,
                            'type_remediation' => 'INDIVIDUELLE',
                            'diagnostic' => "Dépassement du seuil d'autonomie post-Live Coding (> {$seuilMinutes} min)",
                            'est_resolu' => 0,
                            'reference' => 'REM_' . $rt->id . '_' . date('Ymd') . '_' . uniqid(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } else {
                        DB::table('remediations')
                            ->where('realisation_tache_id', $rt->id)
                            ->where('est_resolu', 1)
                            ->update(['est_resolu' => 0, 'updated_at' => now()]);
                    }
                }

                $apprenant = $rt->realisationProjet?->apprenant;
                $apprenantsBasculees[] = [
                    'apprenant_id' => $rt->realisationProjet?->apprenant_id,
                    'nom' => trim(($apprenant?->prenom ?? '') . ' ' . ($apprenant?->nom ?? '')),
                    'tache_id' => $rt->tache_id,
                    'tache_titre' => $rt->tache?->titre ?? 'Tutoriel',
                    'demo_apprenant' => $demoTerminee->realisationProjet?->apprenant?->nom,
                ];
            }
        }

        return $apprenantsBasculees;
    }

    /**
     * Page de Détail & Console d'Étayage — Indicateur 06 ($T_intervention).
     */
    public function getIndicateurInterventionDetail(array $filters = []): array
    {
        $hasTableRemediations = Schema::hasTable('remediations');
        $seuilDefaut = 30;

        // Recherche de l'affectation projet cible
        $queryAff = AffectationProjet::with(['groupe', 'projet.taches']);

        if (!empty($filters['affectation_id'])) {
            $queryAff->where('id', $filters['affectation_id']);
        } else {
            if (!empty($filters['projet_id'])) {
                $queryAff->where('projet_id', $filters['projet_id']);
            }
            if (!empty($filters['groupe_ids'])) {
                $queryAff->whereIn('groupe_id', (array) $filters['groupe_ids']);
            }
            if (!empty($filters['formateur_id'])) {
                $queryAff->whereHas('projet', fn($q) => $q->where('formateur_id', $filters['formateur_id']));
            }
        }

        // Prioriser l'affectation ayant une clôture récente ou le plus d'activité
        $affectation = (clone $queryAff)
            ->whereNotNull('date_fin_live_coding_collectif')
            ->latest('date_fin_live_coding_collectif')
            ->first();

        if (!$affectation) {
            $affectation = $queryAff->latest('date_debut')->first()
                ?? AffectationProjet::with(['groupe', 'projet.taches'])->latest('id')->first();
        }

        if (!$affectation) {
            return [
                'session_active' => null,
                'kpis' => [
                    'taux_intervention' => 100.0,
                    'taux_intervention_formate' => '100%',
                    'total_bloques' => 0,
                    'total_remedies' => 0,
                    'en_attente_soutien' => 0,
                    'seuil_minutes' => $seuilDefaut,
                    'zone' => 'excellence',
                    'badge_label' => 'Excellence (>= 85%)',
                    'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                ],
                'apprenants_en_rupture' => [],
                'tous_les_apprenants' => [],
                'remediations_journal' => [],
                'repartition_remediations' => [
                    'trinome' => 0,
                    'atelier' => 0,
                    'individuelle' => 0,
                    'total' => 0,
                ],
                'benchmark_sessions' => [],
            ];
        }

        $seuilMinutes = (int) ($affectation->seuil_blocage_minutes ?? $seuilDefaut);

        // Détection automatique de la fin du Live Coding et synchronisation des retardataires
        $dateFin = $this->determinerDateFinLiveCodingCollectif($affectation);
        $this->synchroniserApprenantsEnRemediation($affectation, $seuilMinutes);

        $isCloture = !empty($dateFin);
        $maintenant = now();
        $echeanceSeuil = $dateFin ? Carbon::parse($dateFin)->addMinutes($seuilMinutes) : null;
        $enDepassement = $echeanceSeuil ? $maintenant->greaterThan($echeanceSeuil) : false;
        $minutesDepuisCloture = $dateFin ? max(0, (int) Carbon::parse($dateFin)->diffInMinutes($maintenant)) : 0;

        // Statut séance
        if (!$isCloture) {
            $statutSeance = 'EN_COURS';
        } elseif (!$enDepassement) {
            $statutSeance = 'DANS_AUTONOMIE';
        } else {
            $statutSeance = 'SEUIL_DEPASSE';
        }

        // Charger les réalisations du projet
        $realisationsProjet = RealisationProjet::where('affectation_projet_id', $affectation->id)
            ->with([
                'apprenant.user',
                'realisationTaches.tache',
                'realisationTaches.etatRealisationTache.workflowTache'
            ])
            ->get();

        $apprenantsEnRemediation = [];
        $totalApprenants = $realisationsProjet->count();

        // RÈGLE STRICTE : Récupérer les tâches éligibles ayant un démonstrateur (is_live_coding = 1) terminé ("A_VALIDER_LIVE_CODING" ou "APPROVED"/"VALIDEE")
        $tachesEligibles = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectation->id))
            ->where('is_live_coding', 1)
            ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->whereIn('code', ['A_VALIDER_LIVE_CODING', 'APPROVED', 'VALIDEE']))
            ->pluck('tache_id')
            ->unique()
            ->toArray();

        if (!empty($tachesEligibles)) {
            foreach ($realisationsProjet as $rp) {
                $apprenant = $rp->apprenant;
                if (!$apprenant) continue;

                $nomComplet = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? '')) ?: 'Apprenant #' . $apprenant->id;
                $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet) . '&background=fee2e2&color=991b1b';

                // Chercher la tâche actuellement en état EN_REMEDIATION ET dont la tâche a fait l'objet d'un Live Coding valide
                $tacheRemediation = $rp->realisationTaches->first(function ($rt) use ($tachesEligibles) {
                    return optional($rt->etatRealisationTache?->workflowTache)->code === 'EN_REMEDIATION'
                        && in_array($rt->tache_id, $tachesEligibles);
                });

                if (!$tacheRemediation) {
                    continue;
                }

                // Déterminer le délai par rapport à la fin du Live Coding spécifique à cette tâche
                $demoTache = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectation->id))
                    ->where('tache_id', $tacheRemediation->tache_id)
                    ->where('is_live_coding', 1)
                    ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->whereIn('code', ['A_VALIDER_LIVE_CODING', 'APPROVED', 'VALIDEE']))
                    ->first();

                $dateFinTache = $demoTache ? $this->determinerDateFinLiveCodingTache($demoTache) : $dateFin;
                $echeanceSeuilTache = $dateFinTache ? Carbon::parse($dateFinTache)->addMinutes($seuilMinutes) : null;

                $minutesRetard = 0;
                $retardFormate = null;
                if ($echeanceSeuilTache && $maintenant->greaterThan($echeanceSeuilTache)) {
                    $minutesRetard = max(1, (int) $echeanceSeuilTache->diffInMinutes($maintenant));
                    $retardFormate = '+' . ($minutesRetard < 60 ? "{$minutesRetard} min" : sprintf('%dh%02d', intdiv($minutesRetard, 60), $minutesRetard % 60));
                } else {
                    $retardFormate = 'En remédiation';
                }

                $apprenantsEnRemediation[] = [
                    'apprenant_id' => $apprenant->id,
                    'matricule' => $apprenant->matricule ?? null,
                    'nom_complet' => $nomComplet,
                    'avatar' => $avatar,
                    'realisation_tache_id' => $tacheRemediation->id,
                    'tache_titre' => $tacheRemediation->tache->titre ?? 'Tutoriel',
                    'wf_code' => 'EN_REMEDIATION',
                    'wf_label' => $tacheRemediation->etatRealisationTache?->nom ?? 'En remédiation',
                    'statut_didactique' => 'EN_REMEDIATION',
                    'badge_statut' => [
                        'label' => 'En remédiation (' . $retardFormate . ')',
                        'class' => 'bg-rose-50 text-rose-700 border-rose-200'
                    ],
                    'retard_formate' => $retardFormate,
                    'minutes_retard' => $minutesRetard,
                ];
            }
        }

        // Récupérer toutes les remédiations enregistrées pour cette affectation
        $remediationsQuery = DB::table('remediations')
            ->join('realisation_taches', 'realisation_taches.id', '=', 'remediations.realisation_tache_id')
            ->join('realisation_projets', 'realisation_projets.id', '=', 'realisation_taches.realisation_projet_id')
            ->where('realisation_projets.affectation_projet_id', $affectation->id);

        if (!empty($tachesEligibles)) {
            $remediationsQuery->whereIn('realisation_taches.tache_id', $tachesEligibles);
        } else {
            $remediationsQuery->whereRaw('1 = 0');
        }

        $allRemediations = Schema::hasTable('remediations')
            ? $remediationsQuery->select('remediations.*', 'realisation_projets.apprenant_id', 'realisation_taches.tache_id')->get()
            : collect();

        $apprenantIdsRemedies = $allRemediations->pluck('apprenant_id')->unique()->values()->toArray();
        $totalApprenantsAyantEuRemediation = count($apprenantIdsRemedies);
        $totalAutonomes = max(0, $totalApprenants - $totalApprenantsAyantEuRemediation);

        $nbEnRemediationActive = count($apprenantsEnRemediation);
        $totalResolus = $allRemediations->where('est_resolu', 1)->pluck('apprenant_id')->unique()->count();

        // 1. Taux d'Autonomie Initiale : (N_autonomes / N_total) * 100
        $tauxAutonomie = $totalApprenants > 0 ? round(($totalAutonomes / $totalApprenants) * 100, 1) : 100.0;

        // 2. Taux de Résolution des Remédiations : (N_résolus / N_remédiés) * 100
        $tauxResolution = $totalApprenantsAyantEuRemediation > 0 ? round(($totalResolus / $totalApprenantsAyantEuRemediation) * 100, 1) : 100.0;

        // Extraction des remédiations résolues avec succès pour l'affichage de l'historique
        $apprenantsRemediationsResolues = [];
        if (Schema::hasTable('remediations') && !empty($tachesEligibles)) {
            $rowsResolues = DB::table('remediations')
                ->join('realisation_taches', 'realisation_taches.id', '=', 'remediations.realisation_tache_id')
                ->join('realisation_projets', 'realisation_projets.id', '=', 'realisation_taches.realisation_projet_id')
                ->join('apprenants', 'apprenants.id', '=', 'realisation_projets.apprenant_id')
                ->join('taches', 'taches.id', '=', 'realisation_taches.tache_id')
                ->where('realisation_projets.affectation_projet_id', $affectation->id)
                ->whereIn('realisation_taches.tache_id', $tachesEligibles)
                ->where('remediations.est_resolu', 1)
                ->select(
                    'apprenants.id as apprenant_id',
                    'apprenants.nom',
                    'apprenants.prenom',
                    'apprenants.matricule',
                    'taches.titre as tache_titre',
                    'realisation_taches.id as realisation_tache_id',
                    'remediations.diagnostic',
                    'remediations.updated_at as date_resolution'
                )
                ->get();

            foreach ($rowsResolues as $row) {
                $nomComplet = trim(($row->prenom ?? '') . ' ' . ($row->nom ?? ''));
                $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet) . '&background=d1fae5&color=065f46';
                $dateRes = $row->date_resolution ? Carbon::parse($row->date_resolution)->format('d/m/Y H:i') : 'Validé';

                $apprenantsRemediationsResolues[] = [
                    'apprenant_id' => $row->apprenant_id,
                    'matricule' => $row->matricule,
                    'nom_complet' => $nomComplet,
                    'avatar' => $avatar,
                    'realisation_tache_id' => $row->realisation_tache_id,
                    'tache_titre' => $row->tache_titre,
                    'statut_didactique' => 'RESOLU',
                    'diagnostic' => $row->diagnostic,
                    'date_resolution' => $dateRes,
                ];
            }
        }

        if ($nbEnRemediationActive === 0) {
            $zone = 'excellence';
            $badgeLabel = 'Excellence (0 remédiation active)';
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($tauxAutonomie >= 60.0) {
            $zone = 'vigilance';
            $badgeLabel = $nbEnRemediationActive . ' en remédiation';
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zone = 'alerte';
            $badgeLabel = $nbEnRemediationActive . ' en remédiation';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        $benchmark = $this->getIndicateurInterventionResponsable(
            !empty($filters['groupe_ids']) ? (array) $filters['groupe_ids'] : [],
            $filters['projet_id'] ?? null
        )['benchmark'] ?? [];

        return [
            'session_active' => [
                'id' => $affectation->id,
                'projet_titre' => $affectation->projet->titre ?? 'Session de Formation',
                'groupe_code' => $affectation->groupe->code ?? 'Groupe',
                'groupe_nom' => $affectation->groupe->nom ?? '',
                'date_fin_live_coding_collectif' => $dateFin ? Carbon::parse($dateFin)->format('d/m/Y H:i') : null,
                'date_fin_heure' => $dateFin ? Carbon::parse($dateFin)->format('H:i') : null,
                'seuil_blocage_minutes' => $seuilMinutes,
                'is_cloture' => $isCloture,
                'statut_seance' => $statutSeance,
                'minutes_depuis_cloture' => $minutesDepuisCloture,
                'total_apprenants' => $totalApprenants,
            ],
            'kpis' => [
                'taux_intervention' => $tauxAutonomie,
                'taux_intervention_formate' => $tauxAutonomie . '%',
                'taux_autonomie' => $tauxAutonomie,
                'taux_autonomie_formate' => $tauxAutonomie . '%',
                'taux_resolution' => $tauxResolution,
                'taux_resolution_formate' => $tauxResolution . '%',
                'total_apprenants' => $totalApprenants,
                'total_en_remediation' => $nbEnRemediationActive,
                'en_attente_soutien' => $nbEnRemediationActive,
                'total_bloques' => $nbEnRemediationActive,
                'total_remedies' => $totalApprenantsAyantEuRemediation,
                'total_autonomes' => $totalAutonomes,
                'total_resolus' => $totalResolus,
                'seuil_minutes' => $seuilMinutes,
                'zone' => $zone,
                'badge_label' => $badgeLabel,
                'badge_class' => $badgeClass,
            ],
            'apprenants_en_remediation' => $apprenantsEnRemediation,
            'apprenants_remediations_resolues' => $apprenantsRemediationsResolues,
            'apprenants_en_rupture' => $apprenantsEnRemediation,
            'tous_les_apprenants' => $apprenantsEnRemediation,
            'remediations_journal' => [],
            'repartition_remediations' => [
                'trinome' => 0,
                'atelier' => 0,
                'individuelle' => 0,
                'total' => 0,
            ],
            'benchmark_sessions' => $benchmark,
        ];
    }

    /**
     * Clôture la séance de Live Coding collectif au tableau (démarre le chronomètre d'autonomie).
     */
    public function cloturerLiveCodingCollectif(int $affectationProjetId, ?Carbon $dateFin = null): bool
    {
        if (!Schema::hasColumn('affectation_projets', 'date_fin_live_coding_collectif')) {
            return false;
        }

        $dateFin = $dateFin ?: Carbon::now();

        return DB::table('affectation_projets')
            ->where('id', $affectationProjetId)
            ->update([
                'date_fin_live_coding_collectif' => $dateFin,
                'updated_at' => Carbon::now(),
            ]) >= 0;
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
            'diagnostic' => $diagnostic ?: 'Consolidation syntaxique et accompagnement post-Live Coding.',
            'est_resolu' => true,
            'reference' => $reference,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Remediation::find($id);
    }
}
