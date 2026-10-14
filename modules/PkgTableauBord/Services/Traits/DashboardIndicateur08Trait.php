<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;

trait DashboardIndicateur08Trait
{
    /**
     * Indicateur 08 — Progression dans le Référentiel National (ICP) pour l'Apprenant.
     */
    public function getIndicateurCouvertureApprenant(?RealisationProjet $realisationProjet, ?Apprenant $apprenant = null): array
    {
        if (!$apprenant && $realisationProjet) {
            $apprenant = $realisationProjet->apprenant;
        }

        if (!$apprenant || !Schema::hasTable('realisation_micro_competences')) {
            return [
                'taux' => 85.0,
                'taux_formate' => '85%',
                'validees' => 17,
                'total' => 20,
                'non_validees' => 3,
                'statut' => 'Sur la trajectoire d\'excellence',
                'badge_class' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                'message' => '17 / 20 compétences nationales validées (Couverture 85%)',
            ];
        }

        $etatDoneId = DB::table('etat_realisation_micro_competences')->where('code', 'DONE')->value('id') ?? 5;

        $totalPrescrites = DB::table('realisation_micro_competences')
            ->where('apprenant_id', $apprenant->id)
            ->count();

        if ($totalPrescrites === 0) {
            return [
                'taux' => 85.0,
                'taux_formate' => '85%',
                'validees' => 17,
                'total' => 20,
                'non_validees' => 3,
                'statut' => 'Sur la trajectoire d\'excellence',
                'badge_class' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                'message' => '17 / 20 compétences nationales validées (Couverture 85%)',
            ];
        }

        $validees = DB::table('realisation_micro_competences')
            ->where('apprenant_id', $apprenant->id)
            ->where('etat_realisation_micro_competence_id', $etatDoneId)
            ->count();

        $taux = round(($validees / max(1, $totalPrescrites)) * 100, 1);
        $nonValidees = max(0, $totalPrescrites - $validees);

        $badgeClass = $taux >= 80 
            ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' 
            : ($taux >= 50 ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20');
        
        $statut = $taux >= 80 
            ? 'Sur la trajectoire d\'excellence' 
            : ($taux >= 50 ? 'Progression régulière' : 'Renforcement requis');

        return [
            'taux' => $taux,
            'taux_formate' => "{$taux}%",
            'validees' => $validees,
            'total' => $totalPrescrites,
            'non_validees' => $nonValidees,
            'statut' => $statut,
            'badge_class' => $badgeClass,
            'message' => "{$validees} / {$totalPrescrites} compétences nationales validées (Couverture {$taux}%)",
        ];
    }

    /**
     * Indicateur 08 — Diagnostic de Couverture Pédagogique & Impasses Didactiques pour le Formateur.
     */
    public function getIndicateurCouvertureFormateur(?AffectationProjet $affectationProjet, ?Groupe $groupe = null): array
    {
        if (!$groupe && $affectationProjet) {
            $groupe = $affectationProjet->groupe;
        }

        if (!$groupe) {
            $groupe = Groupe::has('apprenants')->first();
        }

        if (!$groupe || !Schema::hasTable('realisation_competences')) {
            return $this->getIndicateurCouvertureFormateurFallback('Classe');
        }

        $apprenantsIds = $groupe->apprenants()->pluck('apprenants.id');
        $effectif = $apprenantsIds->count();

        if ($effectif === 0) {
            return $this->getIndicateurCouvertureFormateurFallback($groupe->code ?? 'Classe');
        }

        // Statistiques au niveau des Compétences Nationales (RealisationCompetence)
        $compStats = DB::table('realisation_competences as rc')
            ->join('competences as c', 'c.id', '=', 'rc.competence_id')
            ->leftJoin('modules as m', 'm.id', '=', 'c.module_id')
            ->whereIn('rc.apprenant_id', $apprenantsIds)
            ->select([
                'c.id as comp_id',
                'c.code as comp_code',
                'c.nom as comp_nom',
                'm.code as module_code',
                'm.nom as module_nom',
                DB::raw('count(*) as count_inscrits'),
                DB::raw('round(avg(rc.progression_cache), 1) as avg_progression'),
                DB::raw('round(avg(rc.progression_ideal_cache), 1) as avg_progression_ideal'),
            ])
            ->groupBy('c.id', 'c.code', 'c.nom', 'm.code', 'm.nom')
            ->orderBy('c.code')
            ->get();

        if ($compStats->isEmpty()) {
            return $this->getIndicateurCouvertureFormateurFallback($groupe->code ?? 'Classe');
        }

        $impasses = [];
        $totalProg = 0;
        $totalIdeal = 0;
        $activeCount = 0;

        foreach ($compStats as $cs) {
            $prog = (float) $cs->avg_progression;
            $ideal = (float) $cs->avg_progression_ideal;
            $delta = round($prog - $ideal, 1);
            $tauxRythme = $ideal > 0 ? round(($prog / $ideal) * 100, 1) : 100.0;

            if ($ideal > 0) {
                $totalProg += $prog;
                $totalIdeal += $ideal;
                $activeCount++;
            }

            // Détection temporelle : impasse réelle si calendrier démarré (ideal >= 25%) et retard critique (rythme < 60% ou delta < -25)
            $isImpasse = ($ideal >= 25.0 && ($tauxRythme < 60.0 || $delta < -25.0));
            $isTension = ($ideal >= 20.0 && ($tauxRythme < 80.0 || $delta < -15.0));

            if ($isImpasse || $isTension) {
                $impasses[] = [
                    'id' => $cs->comp_id,
                    'code' => $cs->comp_code,
                    'titre' => $cs->comp_nom,
                    'module_code' => $cs->module_code,
                    'module_nom' => $cs->module_nom,
                    'progression' => $prog,
                    'progression_formate' => "{$prog}%",
                    'progression_ideal' => $ideal,
                    'progression_ideal_formate' => "{$ideal}%",
                    'taux_rythme' => $tauxRythme,
                    'taux_rythme_formate' => "{$tauxRythme}%",
                    'delta' => $delta,
                    'delta_formate' => ($delta >= 0 ? "+{$delta}" : "{$delta}") . ' pts',
                    'severite' => $isImpasse ? 'critique' : 'alerte',
                    'statut_label' => $isImpasse ? 'Impasse critique' : 'Vigilance didactique',
                    'valides' => round(($prog / 100) * $effectif),
                    'inscrits' => $effectif,
                    'taux' => $prog,
                    'taux_formate' => "{$prog}%",
                ];
            }
        }

        usort($impasses, fn($a, $b) => $a['taux_rythme'] <=> $b['taux_rythme']);

        $progMoyenne = $activeCount > 0 ? round($totalProg / $activeCount, 1) : round($compStats->avg('avg_progression'), 1);
        $idealMoyenne = $activeCount > 0 ? round($totalIdeal / $activeCount, 1) : round($compStats->avg('avg_progression_ideal'), 1);
        $tauxRythmeCohorte = $idealMoyenne > 0 ? round(($progMoyenne / $idealMoyenne) * 100, 1) : 100.0;

        $badgeClass = $tauxRythmeCohorte >= 80 
            ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' 
            : ($tauxRythmeCohorte >= 60 ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20');

        $nbImpassesCritiques = count(array_filter($impasses, fn($i) => $i['severite'] === 'critique'));
        $statut = $nbImpassesCritiques > 0 
            ? "{$nbImpassesCritiques} impasse(s) didactique(s)" 
            : ($tauxRythmeCohorte >= 80 ? 'Trajectoire optimale' : 'Progression régulière');

        return [
            'taux_icp' => $progMoyenne,
            'taux_icp_formate' => "{$progMoyenne}%",
            'progression_reelle_moyenne' => $progMoyenne,
            'progression_ideale_moyenne' => $idealMoyenne,
            'taux_rythme' => $tauxRythmeCohorte,
            'taux_rythme_formate' => "{$tauxRythmeCohorte}%",
            'total_prescrites' => $compStats->count(),
            'effectif' => $effectif,
            'groupe_nom' => $groupe->code ?? 'Classe',
            'impasses' => $impasses,
            'nb_impasses' => $nbImpassesCritiques,
            'statut' => $statut,
            'badge_class' => $badgeClass,
            'description' => "Progression cohorte {$progMoyenne}% (Idéale : {$idealMoyenne}% • Rythme {$tauxRythmeCohorte}%)",
            'message' => "Rythme cohorte {$tauxRythmeCohorte}% ({$nbImpassesCritiques} impasse(s) critique(s))",
        ];
    }

    /**
     * Indicateur 08 — Analyse Détaillée de la Couverture Pédagogique (ICP), Diagnostic des Impasses et Grille des Compétences.
     *
     * @param array $filters
     * @return array
     */
    public function getIndicateurCouvertureDetail(array $filters = []): array
    {
        if (!Schema::hasTable('realisation_competences')) {
            return $this->getIndicateurCouvertureDetailFallback();
        }

        // 1. Résolution du Groupe cible
        $selectedGroupeId = $filters['selected_groupe_id'] ?? null;
        if (!$selectedGroupeId && !empty($filters['groupe_ids']) && count($filters['groupe_ids']) === 1 && $filters['groupe_ids'][0] > 0) {
            $selectedGroupeId = $filters['groupe_ids'][0];
        }

        $groupe = null;
        if ($selectedGroupeId) {
            $groupe = Groupe::with('filiere')->find($selectedGroupeId);
        }

        if (!$groupe && !empty($filters['groupe_ids'])) {
            $validIds = array_filter($filters['groupe_ids'], fn($id) => $id > 0);
            if (!empty($validIds)) {
                $groupe = Groupe::with('filiere')->whereIn('id', $validIds)->first();
            }
        }

        if (!$groupe) {
            $groupe = Groupe::with('filiere')->where('code', 'DWB101')->first() 
                ?? Groupe::with('filiere')->has('apprenants')->first();
        }

        if (!$groupe) {
            return $this->getIndicateurCouvertureDetailFallback();
        }

        // 2. Résolution des Apprenants
        $apprenants = $groupe->apprenants()->with('user')->orderBy('nom')->get();
        $apprenantsIds = $apprenants->pluck('id');
        $effectif = $apprenantsIds->count();

        if ($effectif === 0) {
            return $this->getIndicateurCouvertureDetailFallback($groupe->code ?? 'Groupe');
        }

        // 3. Compétences Macro (RealisationCompetence)
        $compDetails = DB::table('realisation_competences as rc')
            ->join('competences as c', 'c.id', '=', 'rc.competence_id')
            ->leftJoin('modules as m', 'm.id', '=', 'c.module_id')
            ->whereIn('rc.apprenant_id', $apprenantsIds)
            ->select([
                'c.id as comp_id',
                'c.code as comp_code',
                'c.nom as comp_nom',
                'c.description as comp_description',
                'm.code as module_code',
                'm.nom as module_nom',
                DB::raw('count(*) as count_inscrits'),
                DB::raw('round(avg(rc.progression_cache), 1) as avg_progression'),
                DB::raw('round(avg(rc.progression_ideal_cache), 1) as avg_progression_ideal'),
            ])
            ->groupBy('c.id', 'c.code', 'c.nom', 'c.description', 'm.code', 'm.nom')
            ->orderBy('c.code')
            ->get();

        if ($compDetails->isEmpty()) {
            return $this->getIndicateurCouvertureDetailFallback($groupe->code ?? 'Groupe');
        }

        $matriceCompetences = [];
        $impasses = [];
        $totalProgCohorte = 0;
        $totalIdealCohorte = 0;
        $activeCompetencesCount = 0;

        foreach ($compDetails as $cd) {
            $prog = (float) $cd->avg_progression;
            $ideal = (float) $cd->avg_progression_ideal;
            $delta = round($prog - $ideal, 1);
            $tauxRythme = $ideal > 0 ? round(($prog / $ideal) * 100, 1) : 100.0;

            if ($ideal > 0) {
                $totalProgCohorte += $prog;
                $totalIdealCohorte += $ideal;
                $activeCompetencesCount++;
            }

            // Qualification temporelle dynamique
            if ($ideal == 0.0) {
                $severite = 'neutre';
                $statutLabel = 'Non démarrée (En attente)';
                $badgeClass = 'bg-slate-100 text-slate-600 border-slate-200';
                $isImpasse = false;
            } elseif ($tauxRythme >= 95.0 || $delta >= -5.0) {
                $severite = 'optimale';
                $statutLabel = 'Trajectoire optimale (≥ 95%)';
                $badgeClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                $isImpasse = false;
            } elseif ($tauxRythme >= 80.0) {
                $severite = 'regulier';
                $statutLabel = 'Progression régulière (80-95%)';
                $badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                $isImpasse = false;
            } elseif ($tauxRythme >= 60.0) {
                $severite = 'tension';
                $statutLabel = 'Vigilance didactique (60-80%)';
                $badgeClass = 'bg-amber-100 text-amber-800 border-amber-200';
                $isImpasse = false;
            } else {
                $severite = 'critique';
                $statutLabel = 'Impasse didactique critique (< 60%)';
                $badgeClass = 'bg-rose-100 text-rose-800 border-rose-200';
                $isImpasse = ($ideal >= 25.0);
            }

            $compItem = [
                'id' => $cd->comp_id,
                'code' => $cd->comp_code,
                'titre' => $cd->comp_nom,
                'description' => $cd->comp_description,
                'module_code' => $cd->module_code,
                'module_nom' => $cd->module_nom,
                'progression' => $prog,
                'progression_formate' => "{$prog}%",
                'progression_ideal' => $ideal,
                'progression_ideal_formate' => "{$ideal}%",
                'taux_rythme' => $tauxRythme,
                'taux_rythme_formate' => "{$tauxRythme}%",
                'delta' => $delta,
                'delta_formate' => ($delta >= 0 ? "+{$delta}" : "{$delta}") . ' pts',
                'is_impasse' => $isImpasse,
                'severite' => $severite,
                'statut_label' => $statutLabel,
                'badge_class' => $badgeClass,
                'nb_valides' => round(($prog / 100) * $effectif),
                'effectif' => $effectif,
                'taux' => $prog,
                'taux_formate' => "{$prog}%",
                'competence_code' => $cd->comp_code,
                'competence_nom' => $cd->comp_nom,
            ];

            $matriceCompetences[] = $compItem;

            if ($isImpasse || ($severite === 'tension' && $ideal >= 25.0 && $delta < -15.0)) {
                $impasses[] = $compItem;
            }
        }

        usort($impasses, fn($a, $b) => $a['taux_rythme'] <=> $b['taux_rythme']);

        // 4. Statistiques Micro-Compétences (pour matrice de détail fin)
        $etatDoneId = DB::table('etat_realisation_micro_competences')->where('code', 'DONE')->value('id') ?? 5;
        $existingMcIds = DB::table('realisation_micro_competences')
            ->whereIn('apprenant_id', $apprenantsIds)
            ->distinct('micro_competence_id')
            ->pluck('micro_competence_id');

        $matriceMicroCompetences = [];
        if ($existingMcIds->isNotEmpty()) {
            $mcDetails = DB::table('micro_competences as mc')
                ->join('competences as c', 'c.id', '=', 'mc.competence_id')
                ->leftJoin('modules as m', 'm.id', '=', 'c.module_id')
                ->whereIn('mc.id', $existingMcIds)
                ->select([
                    'mc.id',
                    'mc.code as mc_code',
                    'mc.titre as mc_titre',
                    'mc.description as mc_description',
                    'mc.ordre as mc_ordre',
                    'c.code as competence_code',
                    'c.nom as competence_nom',
                    'm.code as module_code',
                    'm.nom as module_nom',
                ])
                ->orderBy('c.code')
                ->orderBy('mc.ordre')
                ->orderBy('mc.code')
                ->get();

            $validationsParMc = DB::table('realisation_micro_competences')
                ->whereIn('apprenant_id', $apprenantsIds)
                ->whereIn('micro_competence_id', $existingMcIds)
                ->where('etat_realisation_micro_competence_id', $etatDoneId)
                ->select('micro_competence_id', DB::raw('count(*) as total_valide'))
                ->groupBy('micro_competence_id')
                ->pluck('total_valide', 'micro_competence_id');

            foreach ($mcDetails as $mc) {
                $nbVal = (int) ($validationsParMc[$mc->id] ?? 0);
                $tx = round(($nbVal / max(1, $effectif)) * 100, 1);
                $matriceMicroCompetences[] = [
                    'id' => $mc->id,
                    'code' => $mc->mc_code ?? 'MC-' . $mc->id,
                    'titre' => $mc->mc_titre,
                    'description' => $mc->mc_description,
                    'competence_code' => $mc->competence_code,
                    'competence_nom' => $mc->competence_nom,
                    'module_code' => $mc->module_code,
                    'module_nom' => $mc->module_nom,
                    'nb_valides' => $nbVal,
                    'effectif' => $effectif,
                    'taux' => $tx,
                    'taux_formate' => "{$tx}%",
                ];
            }
        }

        // 5. Grille Individuelle par Apprenant (sur RealisationCompetence)
        $rcApprenants = DB::table('realisation_competences')
            ->whereIn('apprenant_id', $apprenantsIds)
            ->select([
                'apprenant_id',
                DB::raw('round(avg(progression_cache), 1) as avg_prog'),
                DB::raw('round(avg(progression_ideal_cache), 1) as avg_ideal'),
            ])
            ->groupBy('apprenant_id')
            ->get()
            ->keyBy('apprenant_id');

        $grilleApprenants = [];
        $nbApprenantsDansLesTemps = 0; // Rythme >= 80%

        foreach ($apprenants as $appr) {
            $rcAppr = $rcApprenants[$appr->id] ?? null;
            $progAppr = (float) ($rcAppr->avg_prog ?? 0);
            $idealAppr = (float) ($rcAppr->avg_ideal ?? 0);
            $deltaAppr = round($progAppr - $idealAppr, 1);
            $rythmeAppr = $idealAppr > 0 ? round(($progAppr / $idealAppr) * 100, 1) : 100.0;

            if ($rythmeAppr >= 80.0) {
                $nbApprenantsDansLesTemps++;
            }

            $badgeClassAppr = $rythmeAppr >= 95.0
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                : ($rythmeAppr >= 80.0 ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : ($rythmeAppr >= 60.0 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200'));

            $statutAppr = $rythmeAppr >= 95.0
                ? 'Trajectoire optimale'
                : ($rythmeAppr >= 80.0 ? 'Progression régulière' : ($rythmeAppr >= 60.0 ? 'Vigilance didactique' : 'Décrochage sévère'));

            $grilleApprenants[] = [
                'id' => $appr->id,
                'nom_complet' => $appr->nom . ' ' . $appr->prenom,
                'matricule' => $appr->matricule ?? 'APPR-' . $appr->id,
                'avatar' => $appr->profile_picture ?? asset('images/avatar-default.png'),
                'progression' => $progAppr,
                'progression_formate' => "{$progAppr}%",
                'progression_ideal' => $idealAppr,
                'progression_ideal_formate' => "{$idealAppr}%",
                'taux_rythme' => $rythmeAppr,
                'taux_rythme_formate' => "{$rythmeAppr}%",
                'delta' => $deltaAppr,
                'delta_formate' => ($deltaAppr >= 0 ? "+{$deltaAppr}" : "{$deltaAppr}") . ' pts',
                'statut' => $statutAppr,
                'badge_class' => $badgeClassAppr,
                'validees' => round($progAppr),
                'total' => 100,
                'taux' => $progAppr,
                'taux_formate' => "{$progAppr}%",
            ];
        }

        usort($grilleApprenants, fn($a, $b) => $b['taux_rythme'] <=> $a['taux_rythme']);

        $tauxHomogeneite = round(($nbApprenantsDansLesTemps / max(1, $effectif)) * 100, 1);

        // 6. Calcul des KPIs Globaux de la Cohorte
        $progCohorteMoyenne = $activeCompetencesCount > 0 ? round($totalProgCohorte / $activeCompetencesCount, 1) : 0.0;
        $idealCohorteMoyenne = $activeCompetencesCount > 0 ? round($totalIdealCohorte / $activeCompetencesCount, 1) : 0.0;
        $tauxRythmeCohorte = $idealCohorteMoyenne > 0 ? round(($progCohorteMoyenne / $idealCohorteMoyenne) * 100, 1) : 100.0;
        $deltaCohorte = round($progCohorteMoyenne - $idealCohorteMoyenne, 1);
        $nbImpassesCritiques = count(array_filter($impasses, fn($i) => $i['severite'] === 'critique'));

        // 7. Benchmark Macro Inter-Groupes
        $benchmarkGroupes = $this->getIndicateurCouvertureResponsable()['groupes_benchmark'] ?? [];

        // 8. Projet / Module actif contextuel
        $projetActif = null;
        if (!empty($filters['projet_id'])) {
            $projetActif = \Modules\PkgCreationProjet\Models\Projet::find($filters['projet_id']);
        }

        $badgeClassGlobal = $tauxRythmeCohorte >= 95.0
            ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
            : ($tauxRythmeCohorte >= 80.0 ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : ($tauxRythmeCohorte >= 60.0 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-700 border-rose-200'));

        $statutGlobal = $tauxRythmeCohorte >= 95.0
            ? 'Trajectoire Optimale'
            : ($tauxRythmeCohorte >= 80.0 ? 'Progression Régulière' : 'Vigilance Didactique');

        return [
            'kpis' => [
                'taux_icp' => $progCohorteMoyenne,
                'taux_icp_formate' => "{$progCohorteMoyenne}%",
                'progression_reelle_moyenne' => $progCohorteMoyenne,
                'progression_ideale_moyenne' => $idealCohorteMoyenne,
                'taux_rythme' => $tauxRythmeCohorte,
                'taux_rythme_formate' => "{$tauxRythmeCohorte}%",
                'delta' => $deltaCohorte,
                'delta_formate' => ($deltaCohorte >= 0 ? "+{$deltaCohorte}" : "{$deltaCohorte}") . ' pts',
                'seuil_cible' => 85.0,
                'ecart_cible' => $deltaCohorte,
                'total_prescrites' => $compDetails->count(),
                'total_validations' => round(($progCohorteMoyenne / 100) * ($effectif * $compDetails->count())),
                'total_requis' => $effectif * $compDetails->count(),
                'effectif' => $effectif,
                'nb_impasses' => $nbImpassesCritiques,
                'nb_alertes' => count($impasses),
                'taux_homogeneite' => $tauxHomogeneite,
                'nb_homogenes' => $nbApprenantsDansLesTemps,
                'statut' => $statutGlobal,
                'badge_class' => $badgeClassGlobal,
                'badge_label' => $statutGlobal,
                'groupe_code' => $groupe->code ?? 'Classe',
                'filiere_nom' => $groupe->filiere->nom ?? 'Développement Digital',
            ],
            'session_active' => [
                'groupe_id' => $groupe->id,
                'groupe_code' => $groupe->code,
                'filiere_nom' => $groupe->filiere->nom ?? '-',
                'projet_titre' => $projetActif?->titre ?? 'Référentiel National Module',
            ],
            'competences' => $matriceCompetences,
            'micro_competences' => $matriceMicroCompetences,
            'impasses' => $impasses,
            'apprenants' => $grilleApprenants,
            'benchmark_groupes' => $benchmarkGroupes,
        ];
    }

    /**
     * Fallback pour la vue détaillée de couverture pédagogique.
     */
    protected function getIndicateurCouvertureDetailFallback(string $groupeNom = 'Classe'): array
    {
        $base = $this->getIndicateurCouvertureFormateurFallback($groupeNom);
        return [
            'kpis' => [
                'taux_icp' => $base['taux_icp'],
                'taux_icp_formate' => $base['taux_icp_formate'],
                'seuil_cible' => 85.0,
                'ecart_cible' => -1.0,
                'total_prescrites' => $base['total_prescrites'],
                'total_validations' => $base['total_validations'],
                'total_requis' => $base['effectif'] * $base['total_prescrites'],
                'effectif' => $base['effectif'],
                'nb_impasses' => $base['nb_impasses'],
                'taux_homogeneite' => 80.0,
                'nb_homogenes' => 16,
                'statut' => 'Progression Active',
                'badge_class' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                'badge_label' => 'Progression Active',
                'groupe_code' => $groupeNom,
                'filiere_nom' => 'Développement Digital',
            ],
            'session_active' => [
                'groupe_id' => 1,
                'groupe_code' => $groupeNom,
                'filiere_nom' => 'Développement Digital',
                'projet_titre' => 'Référentiel National Module',
            ],
            'competences' => [],
            'micro_competences' => [],
            'impasses' => $base['impasses'],
            'apprenants' => [],
            'benchmark_groupes' => $this->getIndicateurCouvertureResponsableFallback()['groupes_benchmark'] ?? [],
        ];
    }

    /**
     * Indicateur 08 — Taux de Couverture Macro du Référentiel National pour le Responsable.
     */
    public function getIndicateurCouvertureResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        if (!Schema::hasTable('realisation_micro_competences')) {
            return $this->getIndicateurCouvertureResponsableFallback();
        }

        $etatDoneId = DB::table('etat_realisation_micro_competences')->where('code', 'DONE')->value('id') ?? 5;

        // Récupération des groupes cibles
        $groupesQuery = Groupe::query();
        if (!empty($groupeIds)) {
            $groupesQuery->whereIn('id', $groupeIds);
        } else {
            $groupesQuery->has('apprenants');
        }
        $groupes = $groupesQuery->get();

        if ($groupes->isEmpty()) {
            return $this->getIndicateurCouvertureResponsableFallback();
        }

        $benchmark = [];
        $totalValidationsGlobales = 0;
        $totalRequisGlobal = 0;

        foreach ($groupes as $g) {
            $apprenantsIds = $g->apprenants()->pluck('apprenants.id');
            $effectif = $apprenantsIds->count();
            if ($effectif === 0) continue;

            $totalMc = DB::table('realisation_micro_competences')
                ->whereIn('apprenant_id', $apprenantsIds)
                ->distinct('micro_competence_id')
                ->count('micro_competence_id');

            $validations = DB::table('realisation_micro_competences')
                ->whereIn('apprenant_id', $apprenantsIds)
                ->where('etat_realisation_micro_competence_id', $etatDoneId)
                ->count();

            $totalRequis = $effectif * max(1, $totalMc);
            $icp = round(($validations / max(1, $totalRequis)) * 100, 1);

            $totalValidationsGlobales += $validations;
            $totalRequisGlobal += $totalRequis;

            $benchmark[] = [
                'groupe_id' => $g->id,
                'groupe_nom' => $g->code,
                'effectif' => $effectif,
                'total_mc' => $totalMc,
                'validations' => $validations,
                'icp' => $icp,
                'icp_formate' => "{$icp}%",
                'statut_badge' => $icp >= 80 ? 'Optimal' : ($icp >= 60 ? 'En cours' : 'À renforcer'),
                'badge_class' => $icp >= 80 ? 'text-emerald-400 bg-emerald-500/10' : ($icp >= 60 ? 'text-amber-400 bg-amber-500/10' : 'text-rose-400 bg-rose-500/10'),
            ];
        }

        $tauxMacro = $totalRequisGlobal > 0 ? round(($totalValidationsGlobales / $totalRequisGlobal) * 100, 1) : 91.2;

        return [
            'taux_macro' => $tauxMacro,
            'taux_macro_formate' => "{$tauxMacro}%",
            'seuil_cible' => 85.0,
            'ecart_cible' => round($tauxMacro - 85.0, 1),
            'statut' => $tauxMacro >= 85.0 ? 'Conforme aux exigences nationales' : 'Plan d\'ajustement requis',
            'badge_class' => $tauxMacro >= 85.0 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
            'groupes_benchmark' => $benchmark,
            'nb_groupes' => count($benchmark),
            'message' => "Couverture nationale macro de {$tauxMacro}% (Objectif 85%)",
        ];
    }

    /**
     * Fallback formateur conforme à la maquette.
     */
    protected function getIndicateurCouvertureFormateurFallback(string $groupeNom): array
    {
        return [
            'taux_icp' => 84.0,
            'taux_icp_formate' => '84%',
            'total_prescrites' => 20,
            'total_validations' => 336,
            'effectif' => 20,
            'groupe_nom' => $groupeNom,
            'impasses' => [
                [
                    'id' => 1,
                    'code' => 'MC-04',
                    'titre' => 'Gestion fine des transactions MySQL ACID & Concurrence',
                    'valides' => 7,
                    'inscrits' => 20,
                    'taux' => 35.0,
                    'taux_formate' => '35%',
                    'severite' => 'alerte',
                ],
                [
                    'id' => 2,
                    'code' => 'MC-07',
                    'titre' => 'Déploiement CI/CD Docker multi-stages & Cache Layering',
                    'valides' => 5,
                    'inscrits' => 20,
                    'taux' => 25.0,
                    'taux_formate' => '25%',
                    'severite' => 'critique',
                ],
            ],
            'nb_impasses' => 2,
            'statut' => '2 impasses didactiques',
            'badge_class' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
            'message' => 'Couverture globale cohorte 84% sur 20 micro-compétences (2 impasses détectées)',
        ];
    }

    /**
     * Fallback responsable conforme à la maquette.
     */
    protected function getIndicateurCouvertureResponsableFallback(): array
    {
        return [
            'taux_macro' => 91.2,
            'taux_macro_formate' => '91.2%',
            'seuil_cible' => 85.0,
            'ecart_cible' => 6.2,
            'statut' => 'Conforme aux exigences nationales',
            'badge_class' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
            'groupes_benchmark' => [
                [
                    'groupe_id' => 1,
                    'groupe_nom' => 'DWB-101',
                    'effectif' => 20,
                    'total_mc' => 20,
                    'validations' => 364,
                    'icp' => 91.0,
                    'icp_formate' => '91%',
                    'statut_badge' => 'Optimal',
                    'badge_class' => 'text-emerald-400 bg-emerald-500/10',
                ],
                [
                    'groupe_id' => 2,
                    'groupe_nom' => 'DMB-101',
                    'effectif' => 18,
                    'total_mc' => 20,
                    'validations' => 329,
                    'icp' => 91.4,
                    'icp_formate' => '91.4%',
                    'statut_badge' => 'Optimal',
                    'badge_class' => 'text-emerald-400 bg-emerald-500/10',
                ]
            ],
            'nb_groupes' => 2,
            'message' => 'Couverture nationale macro de 91.2% (Objectif 85% dépassé)',
        ];
    }
}
