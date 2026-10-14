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
     * Formate une durée en minutes :
     * - Si 0 : '0 min'
     * - Si < 60 min : 'X min' (ex: 6 min au lieu de 0h06)
     * - Si >= 60 min : 'XhYY' (ex: 1h01, 1h51)
     *
     * @param float $minutes
     * @return string
     */
    protected function formatDureeMin(float $minutes): string
    {
        $minutesRound = (int) round($minutes);
        if ($minutesRound <= 0) {
            return '0 min';
        }

        $h = (int) floor($minutesRound / 60);
        $m = (int) ($minutesRound % 60);

        if ($h === 0) {
            return $m . ' min';
        }

        return sprintf('%dh%02d', $h, $m);
    }

    /**
     * Détermine la durée d'une session ou d'un ensemble de projets en semaines.
     * Si déterminé par date_debut et date_fin d'affectation, prend ce qui est indiqué dans l'affectation.
     * Sinon, repli par défaut sur 2 semaines.
     *
     * @param array $projetIds
     * @return int
     */
    protected function determinerDureeSessionSemaines(array $projetIds): int
    {
        $paramService = app(ParametrePedagogiqueService::class);
        $dureeDefaut = $paramService->getDureeDefautSessionSemaines(); // 2 semaines

        if (empty($projetIds)) {
            return $dureeDefaut;
        }

        $affectations = AffectationProjet::whereIn('projet_id', $projetIds)->get();
        $durees = [];

        foreach ($affectations as $aff) {
            if (!empty($aff->date_debut) && !empty($aff->date_fin)) {
                $debut = \Carbon\Carbon::parse($aff->date_debut);
                $fin = \Carbon\Carbon::parse($aff->date_fin);
                $diffJours = max(1, $debut->diffInDays($fin));
                // Exemple : 11 jours calendaires (2 semaines ouvrées) -> round(11 / 7) = 2 semaines
                $semaines = max(1, (int) round($diffJours / 7.0));
                $durees[] = $semaines;
            }
        }

        if (!empty($durees)) {
            return (int) max($durees);
        }

        return $dureeDefaut;
    }

    /**
     * Moteur de calcul déterministe par acte de la charge logistique ($C_logistique).
     * Repose sur les temps prédéterminés paramétrables (Standard Action-Cost Model).
     * Calculé par Session (capacité formateur : 26h / semaine).
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
        $tSuiviTache = $paramService->getTempsSuiviTacheMinutes(); // 2 min
        $tValidationTache = $paramService->getTempsValidationTacheMinutes(); // 1 min
        $tFeedbackTache = $paramService->getTempsFeedbackTacheMinutes(); // 1 min

        // Durée de la session (en semaines) et volume horaire hebdomadaire du formateur (26h/semaine)
        $nbSemaines = $this->determinerDureeSessionSemaines($projetIds);
        $volumeHebdoFormateur = $paramService->getVolumeHebdoFormateurHeures(); // 26.0 h
        $capaciteTotaleHeures = $nbSemaines * $volumeHebdoFormateur; // ex: 2 * 26h = 52.0h

        // Plafonds rapportés à la durée de la session
        $cibleHebdo = $paramService->getPlafondChargeLogistiqueHeures(); // 3.0 h / sem
        $seuilOptimalHebdo = $paramService->getSeuilChargeOptimalHeures(); // 2.5 h / sem
        $seuilAlerteHebdo = $paramService->getSeuilChargeAlerteHeures(); // 5.0 h / sem

        $cibleMaxHeures = round($nbSemaines * $cibleHebdo, 1); // ex: 2 * 3.0h = 6.0h
        $seuilOptimal = round($nbSemaines * $seuilOptimalHebdo, 1); // ex: 2 * 2.5h = 5.0h
        $seuilAlerte = round($nbSemaines * $seuilAlerteHebdo, 1); // ex: 2 * 5.0h = 10.0h

        if (empty($projetIds)) {
            return [
                'formateur_id' => $formateur?->id,
                'formateur_nom' => $formateur ? trim(($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? '')) : 'N/A',
                'matricule' => $formateur?->matricule ?? '-',
                'initiales' => $formateur ? strtoupper(substr($formateur->prenom ?? '', 0, 1) . substr($formateur->nom ?? '', 0, 1)) : 'F',
                'total_heures' => 0.0,
                'total_heures_formate' => '0 min',
                'total_heures_dec' => '0.0h',
                'nb_semaines' => $nbSemaines,
                'volume_hebdo' => $volumeHebdoFormateur,
                'capacite_totale_heures' => $capaciteTotaleHeures,
                'temps_tutorat_session' => $capaciteTotaleHeures,
                'temps_coaching_hebdo' => $volumeHebdoFormateur,
                'cible_max_heures' => $cibleMaxHeures,
                'seuil_optimal' => $seuilOptimal,
                'seuil_alerte' => $seuilAlerte,
                'pourcentage_cible' => 0,
                'zone' => 'optimal',
                'badge_label' => 'Logistique optimisée',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'taux_disponibilite' => 100.0,
                'details' => [
                    'conception_min' => 0.0,
                    'conception_heures' => 0.0,
                    'conception_formate' => '0 min',
                    'conception_pct' => 0,
                    'suivi_min' => 0.0,
                    'suivi_heures' => 0.0,
                    'suivi_formate' => '0 min',
                    'suivi_pct' => 0,
                    'validation_min' => 0.0,
                    'validation_heures' => 0.0,
                    'validation_formate' => '0 min',
                    'validation_pct' => 0,
                    'feedback_min' => 0.0,
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

        // Si plusieurs sessions sont concernées, calculer la MOYENNE par session (et non la somme cumulée)
        if (count($projetIds) > 1) {
            $kpisSessions = [];
            foreach ($projetIds as $pId) {
                $kpisSessions[] = $this->calculerChargeLogistiqueActivites([$pId], $formateur);
            }
            return $this->agregerMoyenneChargeLogistique($kpisSessions, $formateur, $projetIds);
        }

        // 1. Conception & Préparation (Session unique)
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

        // Le suivi individuel des réalisations relève du temps pédagogique.
        // Seule la gestion administrative du suivi par tâche est décomptée (2 min / tâche, hors pédagogie).
        $nbSuivi = $nbTachesCreees;
        $minSuivi = $nbSuivi * $tSuiviTache;

        // Validations : décompte par tâche validée (1 min / tâche validée, gestion hors pédagogie).
        $nbValidations = $realisations->filter(fn($r) => $r->etatRealisationTache?->workflowTache?->code === 'APPROVED')
            ->pluck('tache_id')
            ->unique()
            ->count();
        $minValidation = $nbValidations * $tValidationTache;

        // 3. Feedbacks & Remarques (1 min par tâche ayant reçu au moins un feedback, gestion hors pédagogie)
        $realIds = $realisations->pluck('id');
        $revisions = RevisionTache::whereIn('realisation_tache_id', $realIds)
            ->whereNotNull('message_formateur')
            ->where('message_formateur', '!=', '')
            ->get();

        $tachesAvecFeedbackIds = RealisationTache::whereIn('id', $revisions->pluck('realisation_tache_id')->unique())
            ->pluck('tache_id')
            ->unique();

        $nbTachesAvecFeedback = $tachesAvecFeedbackIds->count();
        $totalFbDistincts = $revisions->count();
        $totalCaracteres = $revisions->sum(fn($r) => mb_strlen((string) $r->message_formateur));

        $totalFbMinutes = $nbTachesAvecFeedback * $tFeedbackTache; // 1 min / tâche ayant reçu au moins un feedback

        // Totaux
        $minTotal = $minConception + $minSuivi + $minValidation + $totalFbMinutes;
        $totalHeures = round($minTotal / 60.0, 1);
        $conceptionHeures = round($minConception / 60.0, 1);
        $suiviHeures = round($minSuivi / 60.0, 1);
        $validationHeures = round($minValidation / 60.0, 1);
        $feedbackHeures = round($totalFbMinutes / 60.0, 1);

        $formatDureeMin = fn(float $minutes): string => $this->formatDureeMin($minutes);

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

        // Disponibilité tutorat direct (rapportée à la capacité totale de la session : nbSemaines * 26h)
        $tempsTutoratSession = max(0, $capaciteTotaleHeures - $totalHeures);
        $tempsCoachingHebdo = round($tempsTutoratSession / max(1, $nbSemaines), 1);
        $tauxDispo = $capaciteTotaleHeures > 0 ? round(($tempsTutoratSession / $capaciteTotaleHeures) * 100, 1) : 100.0;
        $pourcentageCible = min(round(($totalHeures / max(0.1, $cibleMaxHeures)) * 100), 100);

        // Décomposition détaillée pour modale
        $decompteItems = [
            [
                'poste' => 'Conception',
                'action' => 'Création de Session / Projet',
                'quantite' => $nbProjets,
                'bareme' => $tCreationProjet . ' min / projet',
                'total_min' => $minProj,
                'total_heures' => round($minProj / 60.0, 1),
                'total_duree_formate' => $formatDureeMin($minProj),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Mobilisation d\'UA (Compétences)',
                'quantite' => $nbMobilisations,
                'bareme' => $tMobUa . ' min / UA',
                'total_min' => $minMob,
                'total_heures' => round($minMob / 60.0, 1),
                'total_duree_formate' => $formatDureeMin($minMob),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Conception des Tâches du Projet',
                'quantite' => $nbTachesCreees,
                'bareme' => $tCreationTache . ' min / tâche',
                'total_min' => $minTaches,
                'total_heures' => round($minTaches / 60.0, 1),
                'total_duree_formate' => $formatDureeMin($minTaches),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Affectation de la Session au Groupe',
                'quantite' => $nbAffectations,
                'bareme' => $tAffectation . ' min / affectation',
                'total_min' => $minAff,
                'total_heures' => round($minAff / 60.0, 1),
                'total_duree_formate' => $formatDureeMin($minAff),
            ],
            [
                'poste' => 'Suivi',
                'action' => 'Suivi Administratif par Tâche (gestion hors pédagogie)',
                'quantite' => $nbSuivi . ' tâche' . ($nbSuivi > 1 ? 's' : ''),
                'bareme' => $tSuiviTache . ' min / tâche',
                'total_min' => $minSuivi,
                'total_heures' => round($minSuivi / 60.0, 1),
                'total_duree_formate' => $formatDureeMin($minSuivi),
            ],
            [
                'poste' => 'Validation',
                'action' => 'Validation Administrative des Tâches (gestion hors pédagogie)',
                'quantite' => $nbValidations . ' tâche' . ($nbValidations > 1 ? 's' : '') . ' validée' . ($nbValidations > 1 ? 's' : ''),
                'bareme' => $tValidationTache . ' min / tâche',
                'total_min' => $minValidation,
                'total_heures' => round($minValidation / 60.0, 1),
                'total_duree_formate' => $formatDureeMin($minValidation),
            ],
            [
                'poste' => 'Feedbacks',
                'action' => 'Feedbacks & Remarques par Tâche (gestion hors pédagogie)',
                'quantite' => $nbTachesAvecFeedback . ' tâche' . ($nbTachesAvecFeedback > 1 ? 's' : '') . ' (' . $totalFbDistincts . ' msgs)',
                'bareme' => $tFeedbackTache . ' min / tâche avec feedback',
                'total_min' => round($totalFbMinutes),
                'total_heures' => $feedbackHeures,
                'total_duree_formate' => $formatDureeMin($totalFbMinutes),
            ],
            [
                'poste' => 'Exclu',
                'action' => 'Consultation Tableau de Bord & Kanban',
                'quantite' => '-',
                'bareme' => '0 min (Observation pédagogique)',
                'total_min' => 0,
                'total_heures' => 0.0,
                'total_duree_formate' => '0 min',
            ],
            [
                'poste' => 'Exclu',
                'action' => 'Génération des Bordereaux & Notes',
                'quantite' => '-',
                'bareme' => '0 min (Automatisé par SoliLMS)',
                'total_min' => 0,
                'total_heures' => 0.0,
                'total_duree_formate' => '0 min',
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
            'nb_semaines' => $nbSemaines,
            'volume_hebdo' => $volumeHebdoFormateur,
            'capacite_totale_heures' => $capaciteTotaleHeures,
            'temps_tutorat_session' => $tempsTutoratSession,
            'temps_coaching_hebdo' => $tempsCoachingHebdo,
            'cible_max_heures' => $cibleMaxHeures,
            'seuil_optimal' => $seuilOptimal,
            'seuil_alerte' => $seuilAlerte,
            'pourcentage_cible' => $pourcentageCible,
            'taux_disponibilite' => $tauxDispo,
            'zone' => $zone,
            'badge_label' => $badgeLabel,
            'badge_class' => $badgeClass,
            'details' => [
                'conception_min' => $minConception,
                'conception_heures' => $conceptionHeures,
                'conception_formate' => $formatDureeMin($minConception),
                'conception_pct' => $pctConception,
                'suivi_min' => $minSuivi,
                'suivi_heures' => $suiviHeures,
                'suivi_formate' => $formatDureeMin($minSuivi),
                'suivi_pct' => $pctSuivi,
                'validation_min' => $minValidation,
                'validation_heures' => $validationHeures,
                'validation_formate' => $formatDureeMin($minValidation),
                'validation_pct' => $pctValidation,
                'feedback_min' => $totalFbMinutes,
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
                'nb_taches_avec_feedback' => $nbTachesAvecFeedback,
                'nb_feedbacks_distincts' => $totalFbDistincts,
                'nb_caracteres_feedbacks' => $totalCaracteres,
            ],
            'decompte_items' => $decompteItems,
        ];
    }

    /**
     * Agrège plusieurs sessions pour calculer la MOYENNE par session (et non la somme cumulée).
     *
     * @param array $kpisSessions Liste des KPIs individuels par session
     * @param Formateur|null $formateur Formateur concerné
     * @param array $projetIds IDs des projets concernés
     * @return array
     */
    protected function agregerMoyenneChargeLogistique(array $kpisSessions, ?Formateur $formateur, array $projetIds): array
    {
        $paramService = app(ParametrePedagogiqueService::class);
        $nbSessions = max(count($kpisSessions), 1);

        // Sommes brutes pour calcul des moyennes
        $totalMinConception = array_sum(array_map(fn($k) => $k['details']['conception_min'] ?? ($k['details']['conception_heures'] * 60.0), $kpisSessions));
        $totalMinSuivi = array_sum(array_map(fn($k) => $k['details']['suivi_min'] ?? ($k['details']['suivi_heures'] * 60.0), $kpisSessions));
        $totalMinValidation = array_sum(array_map(fn($k) => $k['details']['validation_min'] ?? ($k['details']['validation_heures'] * 60.0), $kpisSessions));
        $totalMinFeedback = array_sum(array_map(fn($k) => $k['details']['feedback_min'] ?? ($k['details']['feedback_heures'] * 60.0), $kpisSessions));
        $totalMinCumule = $totalMinConception + $totalMinSuivi + $totalMinValidation + $totalMinFeedback;

        // Moyennes par session
        $moyMinConception = $totalMinConception / $nbSessions;
        $moyMinSuivi = $totalMinSuivi / $nbSessions;
        $moyMinValidation = $totalMinValidation / $nbSessions;
        $moyMinFeedback = $totalMinFeedback / $nbSessions;
        $moyMinTotal = $totalMinCumule / $nbSessions;

        $totalHeures = round($moyMinTotal / 60.0, 1);
        $conceptionHeures = round($moyMinConception / 60.0, 1);
        $suiviHeures = round($moyMinSuivi / 60.0, 1);
        $validationHeures = round($moyMinValidation / 60.0, 1);
        $feedbackHeures = round($moyMinFeedback / 60.0, 1);

        // Durée moyenne d'une session (en semaines) et plafonds associés
        $nbSemaines = (int) max(1, round(array_sum(array_map(fn($k) => $k['nb_semaines'] ?? 2, $kpisSessions)) / $nbSessions));
        $volumeHebdoFormateur = $paramService->getVolumeHebdoFormateurHeures(); // 26.0h
        $capaciteTotaleHeures = $nbSemaines * $volumeHebdoFormateur;

        $cibleMaxHeures = round($nbSemaines * $paramService->getPlafondChargeLogistiqueHeures(), 1);
        $seuilOptimal = round($nbSemaines * $paramService->getSeuilChargeOptimalHeures(), 1);
        $seuilAlerte = round($nbSemaines * $paramService->getSeuilChargeAlerteHeures(), 1);

        $pctConception = $moyMinTotal > 0 ? round(($moyMinConception / $moyMinTotal) * 100) : 0;
        $pctSuivi = $moyMinTotal > 0 ? round(($moyMinSuivi / $moyMinTotal) * 100) : 0;
        $pctValidation = $moyMinTotal > 0 ? round(($moyMinValidation / $moyMinTotal) * 100) : 0;
        $pctFeedbacks = $moyMinTotal > 0 ? round(($moyMinFeedback / $moyMinTotal) * 100) : 0;

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

        $tempsTutoratSession = max(0, $capaciteTotaleHeures - $totalHeures);
        $tempsCoachingHebdo = round($tempsTutoratSession / max(1, $nbSemaines), 1);
        $tauxDispo = $capaciteTotaleHeures > 0 ? round(($tempsTutoratSession / $capaciteTotaleHeures) * 100, 1) : 100.0;
        $pourcentageCible = min(round(($totalHeures / max(0.1, $cibleMaxHeures)) * 100), 100);

        // Moyennes de quantités par session
        $moyMobilisations = round(array_sum(array_map(fn($k) => $k['quantites']['nb_mobilisations'] ?? 0, $kpisSessions)) / $nbSessions, 1);
        $moyTachesCreees = round(array_sum(array_map(fn($k) => $k['quantites']['nb_taches_creees'] ?? 0, $kpisSessions)) / $nbSessions, 1);
        $moyAffectations = round(array_sum(array_map(fn($k) => $k['quantites']['nb_affectations'] ?? 0, $kpisSessions)) / $nbSessions, 1);
        $moySuivi = round(array_sum(array_map(fn($k) => $k['quantites']['nb_suivi'] ?? 0, $kpisSessions)) / $nbSessions, 1);
        $moyValidations = round(array_sum(array_map(fn($k) => $k['quantites']['nb_validations'] ?? 0, $kpisSessions)) / $nbSessions, 1);
        $moyTachesAvecFeedback = round(array_sum(array_map(fn($k) => $k['quantites']['nb_taches_avec_feedback'] ?? 0, $kpisSessions)) / $nbSessions, 1);
        $moyFeedbacksDistincts = round(array_sum(array_map(fn($k) => $k['quantites']['nb_feedbacks_distincts'] ?? 0, $kpisSessions)) / $nbSessions, 1);
        $totalCaracteres = array_sum(array_map(fn($k) => $k['quantites']['nb_caracteres_feedbacks'] ?? 0, $kpisSessions));

        // Décomposition détaillée pour modale (exprimée en moyenne par session)
        $decompteItems = [
            [
                'poste' => 'Conception',
                'action' => 'Création de Session / Projet',
                'quantite' => '1 session (moy. sur ' . $nbSessions . ' sessions)',
                'bareme' => $paramService->getTempsCreationProjetMinutes() . ' min / session',
                'total_min' => round($paramService->getTempsCreationProjetMinutes()),
                'total_heures' => round($paramService->getTempsCreationProjetMinutes() / 60.0, 1),
                'total_duree_formate' => $this->formatDureeMin($paramService->getTempsCreationProjetMinutes()),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Mobilisation d\'UA (Compétences)',
                'quantite' => $moyMobilisations . ' UA / session',
                'bareme' => $paramService->getTempsMobilisationUaMinutes() . ' min / UA',
                'total_min' => round($moyMobilisations * $paramService->getTempsMobilisationUaMinutes()),
                'total_heures' => round(($moyMobilisations * $paramService->getTempsMobilisationUaMinutes()) / 60.0, 1),
                'total_duree_formate' => $this->formatDureeMin($moyMobilisations * $paramService->getTempsMobilisationUaMinutes()),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Conception des Tâches du Projet',
                'quantite' => $moyTachesCreees . ' tâches / session',
                'bareme' => $paramService->getTempsCreationTacheMinutes() . ' min / tâche',
                'total_min' => round($moyTachesCreees * $paramService->getTempsCreationTacheMinutes()),
                'total_heures' => round(($moyTachesCreees * $paramService->getTempsCreationTacheMinutes()) / 60.0, 1),
                'total_duree_formate' => $this->formatDureeMin($moyTachesCreees * $paramService->getTempsCreationTacheMinutes()),
            ],
            [
                'poste' => 'Conception',
                'action' => 'Affectation de la Session au Groupe',
                'quantite' => $moyAffectations . ' affectation / session',
                'bareme' => $paramService->getTempsAffectationGroupeMinutes() . ' min / affectation',
                'total_min' => round($moyAffectations * $paramService->getTempsAffectationGroupeMinutes()),
                'total_heures' => round(($moyAffectations * $paramService->getTempsAffectationGroupeMinutes()) / 60.0, 1),
                'total_duree_formate' => $this->formatDureeMin($moyAffectations * $paramService->getTempsAffectationGroupeMinutes()),
            ],
            [
                'poste' => 'Suivi',
                'action' => 'Suivi Administratif par Tâche (gestion hors pédagogie)',
                'quantite' => $moySuivi . ' tâches / session',
                'bareme' => $paramService->getTempsSuiviTacheMinutes() . ' min / tâche',
                'total_min' => round($moyMinSuivi),
                'total_heures' => round($moyMinSuivi / 60.0, 1),
                'total_duree_formate' => $this->formatDureeMin($moyMinSuivi),
            ],
            [
                'poste' => 'Validation',
                'action' => 'Validation Administrative des Tâches (gestion hors pédagogie)',
                'quantite' => $moyValidations . ' tâches validées / session',
                'bareme' => $paramService->getTempsValidationTacheMinutes() . ' min / tâche',
                'total_min' => round($moyMinValidation),
                'total_heures' => round($moyMinValidation / 60.0, 1),
                'total_duree_formate' => $this->formatDureeMin($moyMinValidation),
            ],
            [
                'poste' => 'Feedbacks',
                'action' => 'Feedbacks & Remarques par Tâche (gestion hors pédagogie)',
                'quantite' => $moyTachesAvecFeedback . ' tâches avec feedback / session',
                'bareme' => $paramService->getTempsFeedbackTacheMinutes() . ' min / tâche avec feedback',
                'total_min' => round($moyMinFeedback),
                'total_heures' => round($moyMinFeedback / 60.0, 1),
                'total_duree_formate' => $this->formatDureeMin($moyMinFeedback),
            ],
            [
                'poste' => 'Exclu',
                'action' => 'Consultation Tableau de Bord & Kanban',
                'quantite' => '-',
                'bareme' => '0 min (Observation pédagogique)',
                'total_min' => 0,
                'total_heures' => 0.0,
                'total_duree_formate' => '0 min',
            ],
            [
                'poste' => 'Exclu',
                'action' => 'Génération des Bordereaux & Notes',
                'quantite' => '-',
                'bareme' => '0 min (Automatisé par SoliLMS)',
                'total_min' => 0,
                'total_heures' => 0.0,
                'total_duree_formate' => '0 min',
            ],
        ];

        return [
            'formateur_id' => $formateur?->id,
            'formateur_nom' => $formateur ? trim(($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? '')) : 'N/A',
            'matricule' => $formateur?->matricule ?? '-',
            'initiales' => $formateur ? strtoupper(substr($formateur->prenom ?? '', 0, 1) . substr($formateur->nom ?? '', 0, 1)) : 'F',
            'is_moyenne_sessions' => true,
            'nb_sessions' => $nbSessions,
            'total_heures' => $totalHeures,
            'total_heures_formate' => $this->formatDureeMin($moyMinTotal),
            'total_heures_dec' => sprintf('%.1fh', $totalHeures),
            'total_cumule_minutes' => $totalMinCumule,
            'total_cumule_heures' => round($totalMinCumule / 60.0, 1),
            'total_cumule_formate' => $this->formatDureeMin($totalMinCumule),
            'nb_semaines' => $nbSemaines,
            'volume_hebdo' => $volumeHebdoFormateur,
            'capacite_totale_heures' => $capaciteTotaleHeures,
            'temps_tutorat_session' => $tempsTutoratSession,
            'temps_coaching_hebdo' => $tempsCoachingHebdo,
            'cible_max_heures' => $cibleMaxHeures,
            'seuil_optimal' => $seuilOptimal,
            'seuil_alerte' => $seuilAlerte,
            'pourcentage_cible' => $pourcentageCible,
            'taux_disponibilite' => $tauxDispo,
            'zone' => $zone,
            'badge_label' => $badgeLabel,
            'badge_class' => $badgeClass,
            'details' => [
                'conception_min' => $moyMinConception,
                'conception_heures' => $conceptionHeures,
                'conception_formate' => $this->formatDureeMin($moyMinConception),
                'conception_pct' => $pctConception,
                'suivi_min' => $moyMinSuivi,
                'suivi_heures' => $suiviHeures,
                'suivi_formate' => $this->formatDureeMin($moyMinSuivi),
                'suivi_pct' => $pctSuivi,
                'validation_min' => $moyMinValidation,
                'validation_heures' => $validationHeures,
                'validation_formate' => $this->formatDureeMin($moyMinValidation),
                'validation_pct' => $pctValidation,
                'feedback_min' => $moyMinFeedback,
                'feedback_heures' => $feedbackHeures,
                'feedback_formate' => $this->formatDureeMin($moyMinFeedback),
                'feedback_pct' => $pctFeedbacks,
            ],
            'quantites' => [
                'nb_projets' => $nbSessions,
                'nb_mobilisations' => $moyMobilisations,
                'nb_taches_creees' => $moyTachesCreees,
                'nb_affectations' => $moyAffectations,
                'nb_suivi' => $moySuivi,
                'nb_validations' => $moyValidations,
                'nb_taches_avec_feedback' => $moyTachesAvecFeedback,
                'nb_feedbacks_distincts' => $moyFeedbacksDistincts,
                'nb_caracteres_feedbacks' => $totalCaracteres,
            ],
            'decompte_items' => $decompteItems,
        ];
    }

    /**
     * Indicateur 04 — Calcul individuel de la charge logistique du formateur ($C_logistique).
     * Si une session ou un projet spécifique est fourni, calcule la charge de cette session.
     *
     * @param Formateur|null $formateur
     * @param mixed $projetOrAffectation (int|Projet|AffectationProjet|null)
     * @return array
     */
    public function getIndicateurChargeLogistiqueFormateur(?Formateur $formateur, mixed $projetOrAffectation = null): array
    {
        if (!$formateur) {
            return $this->calculerChargeLogistiqueActivites([], null);
        }

        $projetId = null;
        if ($projetOrAffectation instanceof \Modules\PkgRealisationProjets\Models\AffectationProjet) {
            $projetId = $projetOrAffectation->projet_id;
        } elseif ($projetOrAffectation instanceof \Modules\PkgCreationProjet\Models\Projet) {
            $projetId = $projetOrAffectation->id;
        } elseif (is_numeric($projetOrAffectation)) {
            $projetId = (int) $projetOrAffectation;
        }

        if ($projetId) {
            $pIds = [$projetId];
        } else {
            $pIds = Projet::where('formateur_id', $formateur->id)->pluck('id')->toArray();
        }

        return $this->calculerChargeLogistiqueActivites($pIds, $formateur);
    }

    /**
     * Indicateur 04 — Calcul macroscopique responsable (Gouvernance & Benchmark Formateurs).
     *
     * @param int|null $formateurId
     * @param int|null $projetId
     * @return array
     */
    public function getIndicateurChargeLogistiqueResponsable(?int $formateurId = null, ?int $projetId = null): array
    {
        $formateursQuery = Formateur::with('user');
        if (!empty($formateurId)) {
            $formateursQuery->where('id', $formateurId);
        }
        $formateurs = $formateursQuery->get();

        $benchmark = [];
        $totalHeuresEquipe = 0.0;

        foreach ($formateurs as $formateur) {
            if (!empty($projetId)) {
                $concerne = Projet::where('id', $projetId)
                    ->where(function ($q) use ($formateur) {
                        $q->where('formateur_id', $formateur->id)
                          ->orWhereHas('affectationProjets.groupe.formateurs', fn($sub) => $sub->where('formateurs.id', $formateur->id));
                    })->exists();
                $pIds = $concerne ? [$projetId] : [];
            } else {
                $pIds = Projet::where('formateur_id', $formateur->id)->pluck('id')->toArray();
            }

            $kpiIndividuel = $this->calculerChargeLogistiqueActivites($pIds, $formateur);
            $totalHeuresEquipe += $kpiIndividuel['total_heures'];
            $benchmark[] = $kpiIndividuel;
        }

        // Tri du benchmark : formateurs les plus chargés en tête
        usort($benchmark, fn($a, $b) => $b['total_heures'] <=> $a['total_heures']);

        $nbFormateurs = max(count($benchmark), 1);
        $chargeMoyenneHeures = round($totalHeuresEquipe / $nbFormateurs, 1);

        $paramService = app(ParametrePedagogiqueService::class);
        $selectedProjetId = !empty($projetId) ? (int) $projetId : null;
        $nbSemaines = $this->determinerDureeSessionSemaines($selectedProjetId ? [$selectedProjetId] : []);
        $seuilOptimal = round($nbSemaines * $paramService->getSeuilChargeOptimalHeures(), 1);
        $seuilAlerte = round($nbSemaines * $paramService->getSeuilChargeAlerteHeures(), 1);
        $plafondCible = round($nbSemaines * $paramService->getPlafondChargeLogistiqueHeures(), 1);

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

        $chargeMoyenneMin = $nbFormateurs > 0 ? ($totalHeuresEquipe * 60.0) / $nbFormateurs : 0;
        $chargeMoyenneFormatee = $this->formatDureeMin($chargeMoyenneMin);

        return [
            'nombre_formateurs' => count($benchmark),
            'charge_moyenne_heures' => $chargeMoyenneHeures,
            'charge_moyenne_formatee' => $chargeMoyenneFormatee,
            'zone' => $zoneMoyenne,
            'badge_label' => $badgeMoyenneLabel,
            'badge_class' => $badgeMoyenneClass,
            'plafond_cible' => $plafondCible,
            'nb_semaines' => $nbSemaines,
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
        $selectedProjetId = !empty($filters['projet_id']) ? (int) $filters['projet_id'] : null;

        $nbSemaines = $this->determinerDureeSessionSemaines($selectedProjetId ? [$selectedProjetId] : []);
        $volumeHebdo = $paramService->getVolumeHebdoFormateurHeures(); // 26.0h
        $capaciteTotaleHeures = $nbSemaines * $volumeHebdo; // ex: 2 * 26h = 52.0h

        $seuilOptimal = round($nbSemaines * $paramService->getSeuilChargeOptimalHeures(), 1);
        $seuilAlerte = round($nbSemaines * $paramService->getSeuilChargeAlerteHeures(), 1);
        $cibleMax = round($nbSemaines * $paramService->getPlafondChargeLogistiqueHeures(), 1);

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
            if ($selectedProjetId) {
                $concerne = Projet::where('id', $selectedProjetId)
                    ->where(function ($q) use ($formateur) {
                        $q->where('formateur_id', $formateur->id)
                          ->orWhereHas('affectationProjets.groupe.formateurs', fn($sub) => $sub->where('formateurs.id', $formateur->id));
                    })->exists();
                $pIds = $concerne ? [$selectedProjetId] : [];
            } else {
                $pIds = Projet::where('formateur_id', $formateur->id)->pluck('id')->toArray();
            }

            $kpi = $this->calculerChargeLogistiqueActivites($pIds, $formateur);
            $totalHeuresEquipe += $kpi['total_heures'];

            $totalMinConception += ($kpi['details']['conception_min'] ?? ($kpi['details']['conception_heures'] * 60.0));
            $totalMinSuivi += ($kpi['details']['suivi_min'] ?? ($kpi['details']['suivi_heures'] * 60.0));
            $totalMinValidation += ($kpi['details']['validation_min'] ?? ($kpi['details']['validation_heures'] * 60.0));
            $totalMinFeedbacks += ($kpi['details']['feedback_min'] ?? ($kpi['details']['feedback_heures'] * 60.0));

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

        $tempsTutoratGlobal = max(0, $capaciteTotaleHeures - $chargeMoyenne);
        $tempsCoachingHebdo = round($tempsTutoratGlobal / max(1, $nbSemaines), 1);
        $tauxDispoGlobal = $capaciteTotaleHeures > 0 ? round(($tempsTutoratGlobal / $capaciteTotaleHeures) * 100, 1) : 100.0;

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

        $formatDureeMin = fn(float $minutes): string => $this->formatDureeMin($minutes);

        $moyMinConception = $nbFormateurs > 0 ? $totalMinConception / $nbFormateurs : 0;
        $moyMinSuivi = $nbFormateurs > 0 ? $totalMinSuivi / $nbFormateurs : 0;
        $moyMinValidation = $nbFormateurs > 0 ? $totalMinValidation / $nbFormateurs : 0;
        $moyMinFeedbacks = $nbFormateurs > 0 ? $totalMinFeedbacks / $nbFormateurs : 0;

        return [
            'semaine' => now()->format('o-\WW'),
            'selected_projet_id' => $selectedProjetId,
            'nb_semaines' => $nbSemaines,
            'volume_hebdo' => $volumeHebdo,
            'capacite_totale_heures' => $capaciteTotaleHeures,
            'temps_coaching_hebdo' => $tempsCoachingHebdo,
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
            'charge_moyenne_formatee' => $formatDureeMin($nbFormateurs > 0 ? $totalMinEquipe / $nbFormateurs : 0),
            'total_heures_equipe' => $totalHeuresEquipe,
            'total_heures_equipe_formate' => $formatDureeMin($totalMinEquipe),
            'taux_disponibilite' => $tauxDispoGlobal,
            'taux_disponibilite_formate' => sprintf('%.1f%%', $tauxDispoGlobal),
            'details_globaux' => [
                'conception_heures' => round($moyMinConception / 60.0, 1),
                'conception_formate' => $formatDureeMin($moyMinConception),
                'conception_pct' => $pctConceptionGlobal,
                'suivi_heures' => round($moyMinSuivi / 60.0, 1),
                'suivi_formate' => $formatDureeMin($moyMinSuivi),
                'suivi_pct' => $pctSuiviGlobal,
                'validation_heures' => round($moyMinValidation / 60.0, 1),
                'validation_formate' => $formatDureeMin($moyMinValidation),
                'validation_pct' => $pctValidationGlobal,
                'feedback_heures' => round($moyMinFeedbacks / 60.0, 1),
                'feedback_formate' => $formatDureeMin($moyMinFeedbacks),
                'feedback_pct' => $pctFeedbacksGlobal,
                'val_feedback_formate' => $formatDureeMin($moyMinValidation + $moyMinFeedbacks),
            ],
            'benchmark' => $roster,
        ];
    }
}
