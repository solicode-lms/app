<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Illuminate\Support\Facades\DB;
use Modules\PkgFormation\Models\Formateur;
use Modules\PkgTableauBord\Models\LogActiviteFormateur;

trait DashboardIndicateur04Trait
{
    /**
     * Indicateur 04 — Calcul individuel de la charge logistique hebdomadaire du formateur (C_logistique).
     */
    public function getIndicateurChargeLogistiqueFormateur(?Formateur $formateur, ?string $semaine = null): array
    {
        $semaine = $semaine ?? now()->format('o-\WW');
        $cibleMaxHeures = 3.0; // Plafond didactique recommandé

        if (!$formateur) {
            return [
                'total_heures' => 0.0,
                'total_heures_formate' => '0h00',
                'cible_max_heures' => $cibleMaxHeures,
                'pourcentage_cible' => 0,
                'zone' => 'optimal',
                'badge_label' => 'Logistique optimisée',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                'details' => [
                    'pilotage_heures' => 0.0,
                    'pilotage_formate' => '0 min',
                    'file_heures' => 0.0,
                    'file_formate' => '0 min',
                    'bordereaux_heures' => 0.0,
                    'bordereaux_formate' => '0 min',
                ],
                'semaine' => $semaine,
            ];
        }

        $logs = DB::table('log_activite_formateurs')
            ->where('formateur_id', $formateur->id)
            ->where('semaine_annee', $semaine)
            ->get();

        $secPilotage = (int) $logs->where('type_activite', LogActiviteFormateur::TYPE_PILOTAGE_KANBAN)->sum('duree_secondes');
        $secFile = (int) $logs->where('type_activite', LogActiviteFormateur::TYPE_GESTION_FILE_FIFO)->sum('duree_secondes');
        $secBordereaux = (int) $logs->where('type_activite', LogActiviteFormateur::TYPE_EXPORT_BORDEREAU)->sum('duree_secondes');
        $secTotal = $secPilotage + $secFile + $secBordereaux;

        $totalHeures = round($secTotal / 3600.0, 1);
        $pilotageHeures = round($secPilotage / 3600.0, 1);
        $fileHeures = round($secFile / 3600.0, 1);
        $bordereauxHeures = round($secBordereaux / 3600.0, 1);

        $formatDuree = function (int $secondes): string {
            $heures = floor($secondes / 3600);
            $minutes = floor(($secondes % 3600) / 60);
            if ($heures > 0) {
                return sprintf('%dh%02d', $heures, $minutes);
            }
            return sprintf('%d min', $minutes);
        };

        // Seuils didactiques
        if ($totalHeures <= 2.5) {
            $zone = 'optimal';
            $badgeLabel = 'Logistique optimisée';
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($totalHeures <= 5.0) {
            $zone = 'vigilance';
            $badgeLabel = 'Charge notable';
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zone = 'alerte';
            $badgeLabel = 'Surcharge bureaucratique';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        $pourcentageCible = min(round(($totalHeures / $cibleMaxHeures) * 100), 100);

        return [
            'formateur_id' => $formateur->id,
            'formateur_nom' => ($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? ''),
            'semaine' => $semaine,
            'total_heures' => $totalHeures,
            'total_heures_formate' => $formatDuree($secTotal),
            'cible_max_heures' => $cibleMaxHeures,
            'pourcentage_cible' => $pourcentageCible,
            'zone' => $zone,
            'badge_label' => $badgeLabel,
            'badge_class' => $badgeClass,
            'details' => [
                'pilotage_heures' => $pilotageHeures,
                'pilotage_formate' => $formatDuree($secPilotage),
                'file_heures' => $fileHeures,
                'file_formate' => $formatDuree($secFile),
                'bordereaux_heures' => $bordereauxHeures,
                'bordereaux_formate' => $formatDuree($secBordereaux),
            ],
        ];
    }

    /**
     * Indicateur 04 — Calcul macroscopique responsable (Gouvernance & Benchmark Formateurs).
     */
    public function getIndicateurChargeLogistiqueResponsable(?string $semaine = null, ?int $formateurId = null): array
    {
        $semaine = $semaine ?? now()->format('o-\WW');
        $formateursQuery = Formateur::with('user');
        if (!empty($formateurId)) {
            $formateursQuery->where('id', $formateurId);
        }
        $formateurs = $formateursQuery->get();

        $benchmark = [];
        $totalHeuresEquipe = 0.0;
        $totalSecPilotage = 0;
        $totalSecFile = 0;
        $totalSecBordereaux = 0;

        foreach ($formateurs as $formateur) {
            $kpiIndividuel = $this->getIndicateurChargeLogistiqueFormateur($formateur, $semaine);
            $totalHeuresEquipe += $kpiIndividuel['total_heures'];

            $benchmark[] = [
                'formateur_id' => $formateur->id,
                'formateur_nom' => trim(($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? '')),
                'matricule' => $formateur->matricule ?? '-',
                'total_heures' => $kpiIndividuel['total_heures'],
                'total_heures_formate' => $kpiIndividuel['total_heures_formate'],
                'zone' => $kpiIndividuel['zone'],
                'badge_label' => $kpiIndividuel['badge_label'],
                'badge_class' => $kpiIndividuel['badge_class'],
                'details' => $kpiIndividuel['details'],
            ];
        }

        // Tri du benchmark : formateurs les plus chargés en tête
        usort($benchmark, fn($a, $b) => $b['total_heures'] <=> $a['total_heures']);

        $nbFormateurs = max(count($benchmark), 1);
        $chargeMoyenneHeures = round($totalHeuresEquipe / $nbFormateurs, 1);

        if ($chargeMoyenneHeures <= 2.5) {
            $zoneMoyenne = 'optimal';
            $badgeMoyenneLabel = 'Logistique optimisée';
            $badgeMoyenneClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        } elseif ($chargeMoyenneHeures <= 5.0) {
            $zoneMoyenne = 'vigilance';
            $badgeMoyenneLabel = 'Charge notable';
            $badgeMoyenneClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zoneMoyenne = 'alerte';
            $badgeMoyenneLabel = 'Surcharge bureaucratique';
            $badgeMoyenneClass = 'bg-rose-50 text-rose-700 border-rose-200';
        }

        return [
            'semaine' => $semaine,
            'nombre_formateurs' => count($benchmark),
            'charge_moyenne_heures' => $chargeMoyenneHeures,
            'charge_moyenne_formatee' => $chargeMoyenneHeures . ' h/sem',
            'zone' => $zoneMoyenne,
            'badge_label' => $badgeMoyenneLabel,
            'badge_class' => $badgeMoyenneClass,
            'plafond_cible' => 3.0,
            'taux_allegement_estime' => 45, // -45% grâce à l'automatisation LMS
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Indicateur 04 — Analyse détaillée approfondie pour la vue dédiée.
     *
     * @param array $filters
     * @return array
     */
    public function getIndicateurChargeLogistiqueDetail(array $filters = []): array
    {
        $paramService = app(\Modules\PkgTableauBord\Services\ParametrePedagogiqueService::class);
        $seuilOptimal = $paramService->getSeuilChargeOptimalHeures();
        $seuilAlerte = $paramService->getSeuilChargeAlerteHeures();
        $cibleMax = $paramService->getPlafondChargeLogistiqueHeures();
        $timeoutInactivite = $paramService->getTimeoutInactiviteMinutes();

        // Semaines disponibles dans la base
        $semainesDisponibles = DB::table('log_activite_formateurs')
            ->distinct()
            ->orderBy('semaine_annee', 'desc')
            ->pluck('semaine_annee')
            ->toArray();

        $semaineCourante = now()->format('o-\WW');
        if (empty($semainesDisponibles)) {
            $semainesDisponibles = [$semaineCourante];
        }

        // Semaine sélectionnée
        $semaine = $filters['semaine'] ?? null;
        if (!$semaine || !in_array($semaine, $semainesDisponibles)) {
            $semaine = in_array($semaineCourante, $semainesDisponibles) ? $semaineCourante : $semainesDisponibles[0];
        }

        // Requête formateurs
        $formateursQuery = Formateur::with('user');
        if (!empty($filters['formateur_id'])) {
            $formateursQuery->where('id', $filters['formateur_id']);
        }
        $allFormateurs = $formateursQuery->orderBy('nom')->get();

        // Logs de la semaine
        $logs = DB::table('log_activite_formateurs')
            ->where('semaine_annee', $semaine)
            ->get();

        $formatDuree = function (int $secondes): string {
            $heures = floor($secondes / 3600);
            $minutes = floor(($secondes % 3600) / 60);
            if ($heures > 0) {
                return sprintf('%dh%02d', $heures, $minutes);
            }
            return sprintf('%d min', $minutes);
        };

        $formatDureeDecimale = function (float $heures): string {
            return sprintf('%.1fh', $heures);
        };

        $roster = [];
        $totalHeuresEquipe = 0.0;
        $totalSecPilotage = 0;
        $totalSecFile = 0;
        $totalSecBordereaux = 0;

        foreach ($allFormateurs as $formateur) {
            $formateurLogs = $logs->where('formateur_id', $formateur->id);

            $secPilot = (int) $formateurLogs->where('type_activite', LogActiviteFormateur::TYPE_PILOTAGE_KANBAN)->sum('duree_secondes');
            $secFile = (int) $formateurLogs->where('type_activite', LogActiviteFormateur::TYPE_GESTION_FILE_FIFO)->sum('duree_secondes');
            $secBord = (int) $formateurLogs->where('type_activite', LogActiviteFormateur::TYPE_EXPORT_BORDEREAU)->sum('duree_secondes');
            $secTot = $secPilot + $secFile + $secBord;

            $totalSecPilotage += $secPilot;
            $totalSecFile += $secFile;
            $totalSecBordereaux += $secBord;

            $totalHeures = round($secTot / 3600.0, 1);
            $totalHeuresEquipe += $totalHeures;

            $pilotHeures = round($secPilot / 3600.0, 1);
            $fileHeures = round($secFile / 3600.0, 1);
            $bordHeures = round($secBord / 3600.0, 1);

            $pilotPct = $secTot > 0 ? round(($secPilot / $secTot) * 100) : 0;
            $filePct = $secTot > 0 ? round(($secFile / $secTot) * 100) : 0;
            $bordPct = $secTot > 0 ? round(($secBord / $secTot) * 100) : 0;

            // Statut & Zone
            if ($totalHeures <= $seuilOptimal) {
                $zone = 'optimal';
                $badgeLabel = 'Optimal';
                $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            } elseif ($totalHeures <= $seuilAlerte) {
                $zone = 'vigilance';
                $badgeLabel = 'Vigilance';
                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
            } else {
                $zone = 'alerte';
                $badgeLabel = 'Alerte';
                $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
            }

            // Disponibilité tutorat direct (base 35h)
            $tauxDispo = round(max(0, (35.0 - $totalHeures) / 35.0) * 100, 1);

            $nom = trim(($formateur->prenom ?? '') . ' ' . ($formateur->nom ?? ''));
            $initiales = strtoupper(substr($formateur->prenom ?? '', 0, 1) . substr($formateur->nom ?? '', 0, 1));

            $roster[] = [
                'id' => $formateur->id,
                'nom' => $nom ?: 'Formateur #' . $formateur->id,
                'matricule' => $formateur->matricule ?? '-',
                'initiales' => $initiales ?: 'F',
                'total_secondes' => $secTot,
                'total_heures' => $totalHeures,
                'total_heures_formate' => $formatDuree($secTot),
                'total_heures_dec' => $formatDureeDecimale($totalHeures),
                'pilotage_secondes' => $secPilot,
                'pilotage_heures' => $pilotHeures,
                'pilotage_formate' => $formatDuree($secPilot),
                'pilotage_pct' => $pilotPct,
                'file_secondes' => $secFile,
                'file_heures' => $fileHeures,
                'file_formate' => $formatDuree($secFile),
                'file_pct' => $filePct,
                'bordereaux_secondes' => $secBord,
                'bordereaux_heures' => $bordHeures,
                'bordereaux_formate' => $formatDuree($secBord),
                'bordereaux_pct' => $bordPct,
                'taux_disponibilite' => $tauxDispo,
                'pourcentage_cible' => min(100, round(($totalHeures / $cibleMax) * 100)),
                'pourcentage_35h' => min(100, round(($totalHeures / 35.0) * 100, 1)),
                'zone' => $zone,
                'badge_label' => $badgeLabel,
                'badge_class' => $badgeClass,
            ];
        }

        // Tri : les plus chargés en tête
        usort($roster, fn($a, $b) => $b['total_heures'] <=> $a['total_heures']);

        $nbFormateurs = max(count($allFormateurs), 1);
        $chargeMoyenne = round($totalHeuresEquipe / $nbFormateurs, 1);
        $totalSecEquipe = $totalSecPilotage + $totalSecFile + $totalSecBordereaux;

        $pctPilotageGlobal = $totalSecEquipe > 0 ? round(($totalSecPilotage / $totalSecEquipe) * 100) : 0;
        $pctFileGlobal = $totalSecEquipe > 0 ? round(($totalSecFile / $totalSecEquipe) * 100) : 0;
        $pctBordereauxGlobal = $totalSecEquipe > 0 ? round(($totalSecBordereaux / $totalSecEquipe) * 100) : 0;

        $tauxDispoGlobal = round(max(0, (35.0 - $chargeMoyenne) / 35.0) * 100, 1);

        if ($chargeMoyenne <= $seuilOptimal) {
            $statut = 'Optimal';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            $statutDescription = 'Charge administrative parfaitement maîtrisée (≤ ' . $seuilOptimal . 'h). Le temps est massivement consacré au tutorat.';
        } elseif ($chargeMoyenne <= $seuilAlerte) {
            $statut = 'Vigilance';
            $statutBadge = 'bg-amber-50 text-amber-700 border-amber-200';
            $statutDescription = 'Charge notable (' . $chargeMoyenne . 'h). Surveillance recommandée pour éviter l\'érosion du temps d\'encadrement.';
        } else {
            $statut = 'Alerte';
            $statutBadge = 'bg-rose-50 text-rose-700 border-rose-200';
            $statutDescription = 'Surcharge bureaucratique critique (> ' . $seuilAlerte . 'h). Automatisation ou allègement des processus urgents.';
        }

        // Historique multi-semaines
        $historiqueSemaines = [];
        foreach ($semainesDisponibles as $sem) {
            $semLogs = DB::table('log_activite_formateurs')->where('semaine_annee', $sem)->get();
            $sPilot = (int) $semLogs->where('type_activite', LogActiviteFormateur::TYPE_PILOTAGE_KANBAN)->sum('duree_secondes');
            $sFile = (int) $semLogs->where('type_activite', LogActiviteFormateur::TYPE_GESTION_FILE_FIFO)->sum('duree_secondes');
            $sBord = (int) $semLogs->where('type_activite', LogActiviteFormateur::TYPE_EXPORT_BORDEREAU)->sum('duree_secondes');
            $sTot = $sPilot + $sFile + $sBord;
            $sHeuresTot = round($sTot / 3600.0, 1);
            $sFormateursCount = max($semLogs->pluck('formateur_id')->unique()->count(), 1);
            $sMoyenne = round($sHeuresTot / $sFormateursCount, 1);
            $historiqueSemaines[] = [
                'semaine' => $sem,
                'charge_moyenne' => $sMoyenne,
                'charge_moyenne_formatee' => sprintf('%.1fh', $sMoyenne),
                'total_heures' => $sHeuresTot,
                'total_heures_formate' => sprintf('%.1fh', $sHeuresTot),
                'pilotage_heures' => round($sPilot / 3600.0, 1),
                'file_heures' => round($sFile / 3600.0, 1),
                'bordereaux_heures' => round($sBord / 3600.0, 1),
                'taux_dispo' => round(max(0, (35.0 - $sMoyenne) / 35.0) * 100, 1),
            ];
        }

        // Comptage par zone
        $nbOptimal = count(array_filter($roster, fn($r) => $r['zone'] === 'optimal'));
        $nbVigilance = count(array_filter($roster, fn($r) => $r['zone'] === 'vigilance'));
        $nbAlerte = count(array_filter($roster, fn($r) => $r['zone'] === 'alerte'));

        return [
            'semaine' => $semaine,
            'semaines_disponibles' => $semainesDisponibles,
            'seuil_optimal' => $seuilOptimal,
            'seuil_alerte' => $seuilAlerte,
            'cible_max' => $cibleMax,
            'timeout_inactivite' => $timeoutInactivite,
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'statut_description' => $statutDescription,
            'nombre_formateurs' => count($allFormateurs),
            'nb_optimal' => $nbOptimal,
            'nb_vigilance' => $nbVigilance,
            'nb_alerte' => $nbAlerte,
            'charge_moyenne_heures' => $chargeMoyenne,
            'charge_moyenne_formatee' => sprintf('%.1fh', $chargeMoyenne),
            'charge_moyenne_duree' => $formatDuree((int) ($chargeMoyenne * 3600)),
            'total_heures_equipe' => $totalHeuresEquipe,
            'total_heures_equipe_formate' => sprintf('%.1fh', $totalHeuresEquipe),
            'taux_disponibilite' => $tauxDispoGlobal,
            'taux_disponibilite_formate' => sprintf('%.1f%%', $tauxDispoGlobal),
            'details_globaux' => [
                'pilotage_heures' => round($totalSecPilotage / 3600.0, 1),
                'pilotage_formate' => $formatDuree($totalSecPilotage),
                'pilotage_pct' => $pctPilotageGlobal,
                'file_heures' => round($totalSecFile / 3600.0, 1),
                'file_formate' => $formatDuree($totalSecFile),
                'file_pct' => $pctFileGlobal,
                'bordereaux_heures' => round($totalSecBordereaux / 3600.0, 1),
                'bordereaux_formate' => $formatDuree($totalSecBordereaux),
                'bordereaux_pct' => $pctBordereauxGlobal,
            ],
            'benchmark' => $roster,
            'historique_semaines' => $historiqueSemaines,
        ];
    }
}
