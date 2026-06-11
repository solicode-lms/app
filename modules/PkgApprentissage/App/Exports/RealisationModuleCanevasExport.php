<?php

namespace Modules\PkgApprentissage\App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RealisationModuleCanevasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithTitle
{
    protected $data;

    public function __construct($data)
    {
        // Trier les données par nom puis par prénom d'apprenant
        $this->data = $data->sort(function ($a, $b) {
            $nomA = $a->apprenant?->nom ?? '';
            $nomB = $b->apprenant?->nom ?? '';
            $cmp = strcasecmp($nomA, $nomB);
            if ($cmp !== 0) {
                return $cmp;
            }
            $prenomA = $a->apprenant?->prenom ?? '';
            $prenomB = $b->apprenant?->prenom ?? '';
            return strcasecmp($prenomA, $prenomB);
        })->values();
    }

    /**
     * Retourne la liste ordonnée des UniteApprentissage extraites des données.
     */
    protected function getUnitesApprentissage(): \Illuminate\Support\Collection
    {
        return $this->data
            ->flatMap(fn($rm) => $rm->realisationCompetences)
            ->flatMap(fn($rc) => $rc->realisationMicroCompetences)
            ->flatMap(fn($rmc) => $rmc->realisationUas)
            ->map(fn($rua) => $rua->uniteApprentissage)
            ->filter()
            ->unique('id')
            ->sortBy('code')
            ->values();
    }

    /**
     * Calcule la note CC sur 20 pour une RealisationUa donnée.
     */
    protected function noteCcSur20($realisationUa): float
    {
        $note   = $realisationUa->note_cc_cache ?? 0;
        $bareme = $realisationUa->bareme_cc_cache ?? 0;

        return $bareme > 0 ? round(($note / $bareme) * 20, 2) : 0;
    }

    /**
     * Retrouve la RealisationUa d'un RealisationModule pour une UA donnée.
     */
    protected function findRealisationUa($realisationModule, int $uniteApprentissageId): ?\Modules\PkgApprentissage\Models\RealisationUa
    {
        return $realisationModule->realisationCompetences
            ->flatMap(fn($rc) => $rc->realisationMicroCompetences)
            ->flatMap(fn($rmc) => $rmc->realisationUas)
            ->first(fn($rua) => $rua->unite_apprentissage_id === $uniteApprentissageId);
    }

    /**
     * Répartit les unités d'apprentissage en 2 ou 3 groupes (CC1, CC2, CC3).
     */
    protected function getCcGroups(): array
    {
        $uas = $this->getUnitesApprentissage();
        $numUas = $uas->count();

        if ($numUas >= 3) {
            $numGroups = 3;
        } elseif ($numUas === 2) {
            $numGroups = 2;
        } else {
            $numGroups = $numUas > 0 ? 1 : 0;
        }

        $groups = [];
        if ($numGroups > 0) {
            $baseSize = floor($numUas / $numGroups);
            $extra = $numUas % $numGroups;
            $startIndex = 0;

            for ($i = 0; $i < $numGroups; $i++) {
                $size = $baseSize + ($i < $extra ? 1 : 0);
                $groups[$i] = $uas->slice($startIndex, $size);
                $startIndex += $size;
            }
        }

        return $groups;
    }

    /**
     * Titre de la feuille Excel (l'onglet).
     */
    public function title(): string
    {
        return 'Canevas';
    }

    /**
     * Génère les en-têtes du canevas d'export.
     */
    public function headings(): array
    {
        return ['CEF', 'Nom', 'Prénom', 'CC1', 'CC2', 'CC3', 'EFM'];
    }

    /**
     * Prépare la collection de données.
     */
    public function collection()
    {
        $ccGroups = $this->getCcGroups();

        return $this->data->map(function ($realisationModule) use ($ccGroups) {
            $apprenant = $realisationModule->apprenant;
            $note      = $realisationModule->note_cache ?? 0;
            $bareme    = $realisationModule->bareme_cache ?? 0;
            
            // Note EFM / 40 (si le barème est 0, on affiche vide)
            $noteSur40 = $bareme > 0 ? round(($note / $bareme) * 40, 2) : '';

            $row = [
                'cef'    => $apprenant ? $apprenant->matricule : '',
                'nom'    => $apprenant ? $apprenant->nom       : '',
                'prenom' => $apprenant ? $apprenant->prenom    : '',
            ];

            // Calculer CC1, CC2, CC3
            for ($ccNum = 1; $ccNum <= 3; $ccNum++) {
                $groupIndex = $ccNum - 1;
                
                if (isset($ccGroups[$groupIndex])) {
                    $groupUas = $ccGroups[$groupIndex];
                    $totalCcGroup = 0;
                    $countCcGroup = 0;

                    foreach ($groupUas as $ua) {
                        $realisationUa = $this->findRealisationUa($realisationModule, $ua->id);
                        $isNoted = $realisationUa && ($realisationUa->bareme_cc_cache ?? 0) > 0;

                        if ($isNoted) {
                            $noteCc = $this->noteCcSur20($realisationUa);
                            $totalCcGroup += $noteCc;
                            $countCcGroup++;
                        }
                    }

                    $row['cc_' . $ccNum] = $countCcGroup > 0 ? round($totalCcGroup / $countCcGroup, 2) : '';
                } else {
                    $row['cc_' . $ccNum] = '';
                }
            }

            // EFM
            $row['efm'] = $noteSur40;

            return $row;
        });
    }

    /**
     * Styles appliqués à la feuille Excel.
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        // Style de l'en-tête (Ligne 1) : Orange avec texte en noir et centré
        $sheet->getStyle("A1:G1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['argb' => '000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'ED7D31'], // Orange professionnel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Alternance de lignes blanche / orange très clair + bordures
        if ($lastRow >= 2) {
            for ($row = 2; $row <= $lastRow; $row++) {
                $color = ($row % 2 === 0) ? 'FDF2E9' : 'FFFFFF';
                $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $color]],
                ]);
            }
            
            $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'AAAAAA']]],
            ]);

            // Centrer le CEF et les notes
            $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            foreach (range('D', 'G') as $col) {
                $sheet->getStyle("{$col}2:{$col}{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        }

        // Auto-size sur toutes les colonnes
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
