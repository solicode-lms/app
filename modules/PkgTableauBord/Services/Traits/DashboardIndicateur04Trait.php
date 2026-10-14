<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Illuminate\Support\Facades\DB;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgCreationProjet\Models\Projet;
use Modules\PkgCreationProjet\Models\MobilisationUa;
use Modules\PkgCreationTache\Models\Tache;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Models\RevisionTache;
use Modules\PkgTableauBord\Services\ParametrePedagogiqueService;

trait DashboardIndicateur04Trait
{
    /**
     * Moteur de calcul déterministe par acte de la charge logistique ($C_logistique).
     * Repose sur les temps prédéterminés paramétrables (Standard Action-Cost Model).
     *
     * @param array $projetIds IDs des projets/sessions à inclure
     * @param Formateur|null $formateur Formateur concerné
     * @return array
     */
    protected function calculerChargeLogistiqueActivites(array $projetIds, ?Formateur $formateur = null): array
    {
        $paramService = app(ParametrePedagogiqueService::class);
        $tCreationProjet = $paramService->getTempsCreationProjetMinutes(); // 15 min
        $tMobUa = $paramService->getTempsMobilisationUaMinutes(); // 1 min
        $tCreationTache = $paramService->getTempsCreationTacheMinutes(); // 3 min
        $tAffectation = $paramService->getTempsAffectationGroupeMinutes(); // 3 min
        $tSuiviTache = $paramService->getTempsSuiviTacheMinutes(); // 1 min
        $tValidationTache = $paramService->getTempsValidationTacheMinutes(); // 1 min
        $tFbMin = $paramService->getTempsFeedbackMinMinutes(); // 1.0 min
        $tFbMax = $paramService->getTempsFeedbackMaxMinutes(); // 5.0 min

        $cibleMaxHeures = $paramService->getPlafondChargeLogistiqueHeures(); // 3.0 h
        $seuilOptimal = $paramService->getSeuilChargeOptimalHeures(); // 2.5 h
        $seuilAlerte = $paramService->getSeuilChargeAlerteHeures(); // 5.0 h

        if (empty($projetIds)) {
            return [
                'formateur_id' => $formateur?->id,
                'formateur_nom' => $formateur ? trim(($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? '')) : 'N/A',
                'matricule' => $formateur?->matricule ?? '-',
                'initiales' => $formateur ? strtoupper(substr($formateur->prenom ?? '', 0, 1) . substr($formateur->nom ?? '', 0, 1)) : 'F',
                'total_heures' => 0.0,
                'total_heures_formate' => '0h00',
                'total_heures_dec' => '0.0h',
                'cible_max_heures' => $cibleMaxHeures,
                'pourcentage_cible' => 0,
                'zone' => 'optimal',
                'badge_label' => 'Logistique optimisée',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'taux_disponibilite' => 100.0,
                'details' => [
                    'conception_heures' => 0.0,
                    'conception_formate' => '0 min',
                    'conception_pct' => 0,
                    'suivi_heures' => 0.0,
                    'suivi_formate' => '0 min',
                    'suivi_pct' => 0,
                    'validation_heures' => 0.0,
                    'validation_formate' => '0 min',
                    'validation_pct' => 0,
                    'feedback_heures' => 0.0,
                    'feedback_formate' => '0 min',
                    'feedback_pct' => 0,
                ],
                'quantites' => [
                    'nb_projets' => 0,
                    'nb_mobilisations' => 0,
                    'nb_taches_creees' => 0,
                    'nb_affectations' => 0,
                    'nb_suivi' => 0,
                    'nb_validations' => 0,
                    'nb_feedbacks_distincts' => 0,
                    'nb_caracteres_feedbacks' => 0,
                ],
                'decompte_items' => [],
            ];
        }

        // 1. Conception & Préparation
        $nbProjets = count($projetIds);
        $nbMobilisations = MobilisationUa::whereIn('projet_id', $projetIds)->count();
        $nbTachesCreees = Tache::whereIn('projet_id', $projetIds)->count();
        $nbAffectations = AffectationProjet::whereIn('projet_id', $projetIds)->count();

        $minProj = $nbProjets * $tCreationProjet;
        $minMob = $nbMobilisations * $tMobUa;
        $minTaches = $nbTachesCreees * $tCreationTache;
        $minAff = $nbAffectations * $tAffectation;
        $minConception = $minProj + $minMob + $minTaches + $minAff;

        // 2. Suivi Opérationnel & Validations
        $affectationIds = AffectationProjet::whereIn('projet_id', $projetIds)->pluck('id');
        $realisations = RealisationTache::whereHas('realisationProjet', fn($q) => $q->whereIn('affectation_projet_id', $affectationIds))
            ->with('etatRealisationTache.workflowTache')
            ->get();

        $nbSuivi = $realisations->count();
        $nbValidations = $realisations->filter(fn($r) => $r->etatRealisationTache?->workflowTache?->code === 'APPROVED')->count();

        $minSuivi = $nbSuivi * $tSuiviTache;
        $minValidation = $nbValidations * $tValidationTache;

        // 3. Feedbacks & Remarques (1 à 5 min par feedback distinct, plafonné à 5 min par tâche)
        $realIds = $realisations->pluck('id');
        $revisions = RevisionTache::whereIn('realisation_tache_id', $realIds)->get();

        $totalFbMinutes = 0.0;
        $totalFbDistincts = 0;
        $totalCaracteres = 0;

        foreach ($revisions->groupBy('realisation_tache_id') as $tacheRevisions) {
            $msgs = $tacheRevisions->pluck('message_formateur')
                ->map(fn($m) => trim((string)$m))
                ->filter()
                ->unique();

            if ($msgs->isEmpty()) {
                continue;
            }

            $totalFbDistincts += $msgs->count();
            $tTache = 0.0;

            foreach ($msgs as $msg) {
                $len = mb_strlen($msg);
                $totalCaracteres += $len;
                // Formule étalonnée : 1 min de base + 1 min / 150 caractères tapés (entre 1 et 5 min)
                $tMsg = min($tFbMax, max($tFbMin, 1.0 + ($len / 150.0)));
                $tTache += $tMsg;
            }

            // Plafonnement didactique : au maximum 5 min par tâche
            $totalFbMinutes += min($tFbMax, $tTache);
        }

        // Totaux
        $minTotal = $minConception + $minSuivi + $minValidation + $totalFbMinutes;
        $totalHeures = round($minTotal / 60.0, 1);
        $conceptionHeures = round($minConception / 60.0, 1);
        $suiviHeures = round($minSuivi / 60.0, 1);
        $validationHeures = round($minValidation / 60.0, 1);
        $feedbackHeures = round($totalFbMinutes / 60.0, 1);

        $formatDureeMin = function (float $minutes): string {
            $h = floor($minutes / 60);
            $m = round($minutes % 60);
            if ($h > 0) {
                return sprintf('%dh%02d', $h, $m);
            }
            return sprintf('%d min', $m);
        };

        $pctConception = $minTotal > 0 ? round(($minConception / $minTotal) * 100) : 0;
        $pctSuivi = $minTotal > 0 ? round(($minSuivi / $minTotal) * 100) : 0;
        $pctValidation = $minTotal > 0 ? round(($minValidation / $minTotal) * 100) : 0;
        $pctFeedbacks = $minTotal > 0 ? round(($totalFbMinutes / $minTotal) * 100) : 0;

        // Zone didactique (rapportée au plafond cible 3.0h)
        if ($totalHeures <= $seuilOptimal) {
            $zone = 'optimal';
            $badgeLabel = 'Logistique optimisée';
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($totalHeures <= $seuilAlerte) {
            $zone = 'vigilance';
            $badgeLabel = 'Charge notable';
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zone = 'alerte';
            $badgeLabel = 'Surcharge bureaucratique';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        $tauxDispo = round(max(0, (35.0 - min(35.0, $totalHeures)) / 35.0) * 100, 1);
        $pourcentageCible = min(round(($totalHeures / $cibleMaxHeures) * 100), 100);

        // Décomposition détaillée pour modale
        $decompteItems = [
            [
                'poste' => 'Conception',
                'action' => 'Création de Session / Projet',
                'quantite' => $nbProjets,
                'bareme' => $tCreationProjet . ' min / projet',
                'total_min' => $minProj,
                'total_heures' => round($minProj / 60.0, 1),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Mobilisation d\'UA (Compétences)',
                'quantite' => $nbMobilisations,
                'bareme' => $tMobUa . ' min / UA',
                'total_min' => $minMob,
                'total_heures' => round($minMob / 60.0, 1),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Conception des Tâches du Projet',
                'quantite' => $nbTachesCreees,
                'bareme' => $tCreationTache . ' min / tâche',
                'total_min' => $minTaches,
                'total_heures' => round($minTaches / 60.0, 1),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Affectation de la Session au Groupe',
                'quantite' => $nbAffectations,
                'bareme' => $tAffectation . ' min / affectation',
                'total_min' => $minAff,
                'total_heures' => round($minAff / 60.0, 1),
            ],
            [
                'poste' => 'Suivi',
                'action' => 'Suivi Opérationnel des Tâches Assignées',
                'quantite' => $nbSuivi,
                'bareme' => $tSuiviTache . ' min / tâche suivie',
                'total_min' => $minSuivi,
                'total_heures' => round($minSuivi / 60.0, 1),
            ],
            [
                'poste' => 'Validation',
                'action' => 'Validation Pédagogique des Livrables',
                'quantite' => $nbValidations,
                'bareme' => $tValidationTache . ' min / validation',
                'total_min' => $minValidation,
                'total_heures' => round($minValidation / 60.0, 1),
            ],
            [
                'poste' => 'Feedbacks',
                'action' => 'Rédaction de Feedbacks & Remarques',
                'quantite' => $totalFbDistincts . ' msgs (' . $totalCaracteres . ' car.)',
                'bareme' => '1 à 5 min / tâche (max 5 min)',
                'total_min' => round($totalFbMinutes),
                'total_heures' => $feedbackHeures,
            ],
            [
                'poste' => 'Exclu',
                'action' => 'Consultation Tableau de Bord & Kanban',
                'quantite' => '-',
                'bareme' => '0 min (Observation pédagogique)',
                'total_min' => 0,
                'total_heures' => 0.0,
            ],
            [
                'poste' => 'Exclu',
                'action' => 'Génération des Bordereaux & Notes',
                'quantite' => '-',
                'bareme' => '0 min (Automatisé par SoliLMS)',
                'total_min' => 0,
                'total_heures' => 0.0,
            ],
        ];

        return [
            'formateur_id' => $formateur?->id,
            'formateur_nom' => $formateur ? trim(($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? '')) : 'N/A',
            'matricule' => $formateur?->matricule ?? '-',
            'initiales' => $formateur ? strtoupper(substr($formateur->prenom ?? '', 0, 1) . substr($formateur->nom ?? '', 0, 1)) : 'F',
            'total_heures' => $totalHeures,
            'total_heures_formate' => $formatDureeMin($minTotal),
            'total_heures_dec' => sprintf('%.1fh', $totalHeures),
            'cible_max_heures' => $cibleMaxHeures,
            'pourcentage_cible' => $pourcentageCible,
            'taux_disponibilite' => $tauxDispo,
            'zone' => $zone,
            'badge_label' => $badgeLabel,
            'badge_class' => $badgeClass,
            'details' => [
                'conception_heures' => $conceptionHeures,
                'conception_formate' => $formatDureeMin($minConception),
                'conception_pct' => $pctConception,
                'suivi_heures' => $suiviHeures,
                'suivi_formate' => $formatDureeMin($minSuivi),
                'suivi_pct' => $pctSuivi,
                'validation_heures' => $validationHeures,
                'validation_formate' => $formatDureeMin($minValidation),
                'validation_pct' => $pctValidation,
                'feedback_heures' => $feedbackHeures,
                'feedback_formate' => $formatDureeMin($totalFbMinutes),
                'feedback_pct' => $pctFeedbacks,
            ],
            'quantites' => [
                'nb_projets' => $nbProjets,
                'nb_mobilisations' => $nbMobilisations,
                'nb_taches_creees' => $nbTachesCreees,
                'nb_affectations' => $nbAffectations,
                'nb_suivi' => $nbSuivi,
                'nb_validations' => $nbValidations,
                'nb_feedbacks_distincts' => $totalFbDistincts,
                'nb_caracteres_feedbacks' => $totalCaracteres,
            ],
            'decompte_items' => $decompteItems,
        ];
    }

    /**
     * Indicateur 04 — Calcul individuel de la charge logistique du formateur ($C_logistique).
     */
    public function getIndicateurChargeLogistiqueFormateur(?Formateur $formateur, ?string $semaine = null): array
    {
        if (!$formateur) {
            return $this->calculerChargeLogistiqueActivites([], null);
        }

        $pIds = Projet::where('formateur_id', $formateur->id)->pluck('id')->toArray();
        return $this->calculerChargeLogistiqueActivites($pIds, $formateur);
    }

    /**
     * Indicateur 04 — Calcul macroscopique responsable (Gouvernance & Benchmark Formateurs).
     */
    public function getIndicateurChargeLogistiqueResponsable(?string $semaine = null, ?int $formateurId = null): array
    {
        $formateursQuery = Formateur::with('user');
        if (!empty($formateurId)) {
            $formateursQuery->where('id', $formateurId);
        }
        $formateurs = $formateursQuery->get();

        $benchmark = [];
        $totalHeuresEquipe = 0.0;

        foreach ($formateurs as $formateur) {
            $pIds = Projet::where('formateur_id', $formateur->id)->pluck('id')->toArray();
            $kpiIndividuel = $this->calculerChargeLogistiqueActivites($pIds, $formateur);
            $totalHeuresEquipe += $kpiIndividuel['total_heures'];
            $benchmark[] = $kpiIndividuel;
        }

        // Tri du benchmark : formateurs les plus chargés en tête
        usort($benchmark, fn($a, $b) => $b['total_heures'] <=> $a['total_heures']);

        $nbFormateurs = max(count($benchmark), 1);
        $chargeMoyenneHeures = round($totalHeuresEquipe / $nbFormateurs, 1);

        $paramService = app(ParametrePedagogiqueService::class);
        $seuilOptimal = $paramService->getSeuilChargeOptimalHeures();
        $seuilAlerte = $paramService->getSeuilChargeAlerteHeures();

        if ($chargeMoyenneHeures <= $seuilOptimal) {
            $zoneMoyenne = 'optimal';
            $badgeMoyenneLabel = 'Logistique optimisée';
            $badgeMoyenneClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($chargeMoyenneHeures <= $seuilAlerte) {
            $zoneMoyenne = 'vigilance';
            $badgeMoyenneLabel = 'Charge notable';
            $badgeMoyenneClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zoneMoyenne = 'alerte';
            $badgeMoyenneLabel = 'Surcharge bureaucratique';
            $badgeMoyenneClass = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        return [
            'nombre_formateurs' => count($benchmark),
            'charge_moyenne_heures' => $chargeMoyenneHeures,
            'charge_moyenne_formatee' => $chargeMoyenneHeures . ' h',
            'zone' => $zoneMoyenne,
            'badge_label' => $badgeMoyenneLabel,
            'badge_class' => $badgeMoyenneClass,
            'plafond_cible' => $paramService->getPlafondChargeLogistiqueHeures(),
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Indicateur 04 — Analyse détaillée approfondie basée sur les actes réels et temps prédéterminés.
     *
     * @param array $filters
     * @return array
     */
    public function getIndicateurChargeLogistiqueDetail(array $filters = []): array
    {
        $paramService = app(ParametrePedagogiqueService::class);
        $seuilOptimal = $paramService->getSeuilChargeOptimalHeures();
        $seuilAlerte = $paramService->getSeuilChargeAlerteHeures();
        $cibleMax = $paramService->getPlafondChargeLogistiqueHeures();

        // Si un projet / session est sélectionné
        $selectedProjetId = !empty($filters['projet_id']) ? (int) $filters['projet_id'] : null;

        $formateursQuery = Formateur::with('user');
        if (!empty($filters['formateur_id'])) {
            $formateursQuery->where('id', $filters['formateur_id']);
        }
        if (!empty($filters['filiere_id'])) {
            $formateursQuery->whereHas('specialites.filiere', fn($q) => $q->where('id', $filters['filiere_id']));
        }
        $allFormateurs = $formateursQuery->orderBy('nom')->get();

        $roster = [];
        $totalHeuresEquipe = 0.0;
        $totalMinConception = 0.0;
        $totalMinSuivi = 0.0;
        $totalMinValidation = 0.0;
        $totalMinFeedbacks = 0.0;

        foreach ($allFormateurs as $formateur) {
            $pQuery = Projet::where('formateur_id', $formateur->id);
            if ($selectedProjetId) {
                $pQuery->where('id', $selectedProjetId);
            }
            $pIds = $pQuery->pluck('id')->toArray();

            $kpi = $this->calculerChargeLogistiqueActivites($pIds, $formateur);
            $totalHeuresEquipe += $kpi['total_heures'];

            $totalMinConception += ($kpi['details']['conception_heures'] * 60.0);
            $totalMinSuivi += ($kpi['details']['suivi_heures'] * 60.0);
            $totalMinValidation += ($kpi['details']['validation_heures'] * 60.0);
            $totalMinFeedbacks += ($kpi['details']['feedback_heures'] * 60.0);

            $roster[] = $kpi;
        }

        // Tri : les plus chargés en tête
        usort($roster, fn($a, $b) => $b['total_heures'] <=> $a['total_heures']);

        $nbFormateurs = max(count($allFormateurs), 1);
        $chargeMoyenne = round($totalHeuresEquipe / $nbFormateurs, 1);
        $totalMinEquipe = $totalMinConception + $totalMinSuivi + $totalMinValidation + $totalMinFeedbacks;

        $pctConceptionGlobal = $totalMinEquipe > 0 ? round(($totalMinConception / $totalMinEquipe) * 100) : 0;
        $pctSuiviGlobal = $totalMinEquipe > 0 ? round(($totalMinSuivi / $totalMinEquipe) * 100) : 0;
        $pctValidationGlobal = $totalMinEquipe > 0 ? round(($totalMinValidation / $totalMinEquipe) * 100) : 0;
        $pctFeedbacksGlobal = $totalMinEquipe > 0 ? round(($totalMinFeedbacks / $totalMinEquipe) * 100) : 0;

        $tauxDispoGlobal = round(max(0, (35.0 - min(35.0, $chargeMoyenne)) / 35.0) * 100, 1);

        if ($chargeMoyenne <= $seuilOptimal) {
            $statut = 'Optimal';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            $statutDescription = 'Charge administrative maîtrisée (≤ ' . $seuilOptimal . 'h). Le temps est massivement consacré au tutorat direct.';
        } elseif ($chargeMoyenne <= $seuilAlerte) {
            $statut = 'Vigilance';
            $statutBadge = 'bg-amber-50 text-amber-700 border-amber-200';
            $statutDescription = 'Charge notable (' . $chargeMoyenne . 'h). Surveillance recommandée pour préserver le temps d\'accompagnement.';
        } else {
            $statut = 'Alerte';
            $statutBadge = 'bg-rose-50 text-rose-700 border-rose-200';
            $statutDescription = 'Surcharge bureaucratique critique (> ' . $seuilAlerte . 'h). Optimisation et automatisation recommandées.';
        }

        $nbOptimal = count(array_filter($roster, fn($r) => $r['zone'] === 'optimal'));
        $nbVigilance = count(array_filter($roster, fn($r) => $r['zone'] === 'vigilance'));
        $nbAlerte = count(array_filter($roster, fn($r) => $r['zone'] === 'alerte'));

        return [
            'semaine' => now()->format('o-\WW'),
            'selected_projet_id' => $selectedProjetId,
            'seuil_optimal' => $seuilOptimal,
            'seuil_alerte' => $seuilAlerte,
            'cible_max' => $cibleMax,
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'statut_description' => $statutDescription,
            'nombre_formateurs' => count($allFormateurs),
            'nb_optimal' => $nbOptimal,
            'nb_vigilance' => $nbVigilance,
            'nb_alerte' => $nbAlerte,
            'charge_moyenne_heures' => $chargeMoyenne,
            'charge_moyenne_formatee' => sprintf('%.1fh', $chargeMoyenne),
            'total_heures_equipe' => $totalHeuresEquipe,
            'total_heures_equipe_formate' => sprintf('%.1fh', $totalHeuresEquipe),
            'taux_disponibilite' => $tauxDispoGlobal,
            'taux_disponibilite_formate' => sprintf('%.1f%%', $tauxDispoGlobal),
            'details_globaux' => [
                'conception_heures' => round($totalMinConception / 60.0, 1),
                'conception_pct' => $pctConceptionGlobal,
                'suivi_heures' => round($totalMinSuivi / 60.0, 1),
                'suivi_pct' => $pctSuiviGlobal,
                'validation_heures' => round($totalMinValidation / 60.0, 1),
                'validation_pct' => $pctValidationGlobal,
                'feedback_heures' => round($totalMinFeedbacks / 60.0, 1),
                'feedback_pct' => $pctFeedbacksGlobal,
            ],
            'benchmark' => $roster,
        ];
    }
}
