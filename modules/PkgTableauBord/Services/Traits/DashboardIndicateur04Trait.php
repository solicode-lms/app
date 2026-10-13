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
    public function getIndicateurChargeLogistiqueResponsable(?string $semaine = null): array
    {
        $semaine = $semaine ?? now()->format('o-\WW');
        $formateurs = Formateur::with('user')->get();

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
}
