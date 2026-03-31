<?php

namespace Modules\PkgApprentissage\App\Exports;

use Modules\PkgApprentissage\App\Exports\Base\BaseRealisationModuleExport;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RealisationModuleExport extends BaseRealisationModuleExport
{
    /**
     * Retourne la liste ordonnée des UniteApprentissage extraites des données.
     * Format : Collection de ['code' => ..., 'nom' => ..., 'id' => ...]
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
            ->sortBy('id')
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
     * Résout le formateur via le dernier projet évalué, avec fallback groupe.
     */
    protected function resolveFormateur($realisationModule): string
    {
        try {
            $realisationUaProjet = $realisationModule
                ->realisationCompetences()
                ->with([
                    'realisationMicroCompetences.realisationUas.realisationUaProjets.realisationTache.realisationProjet.affectationProjet.projet.formateur'
                ])
                ->get()
                ->flatMap(fn($rc) => $rc->realisationMicroCompetences)
                ->flatMap(fn($rmc) => $rmc->realisationUas)
                ->flatMap(fn($rua) => $rua->realisationUaProjets)
                ->sortByDesc('id')
                ->first();

            $formateur = $realisationUaProjet?->realisationTache?->realisationProjet?->affectationProjet?->projet?->formateur;
            if ($formateur) {
                return trim(($formateur->nom ?? '') . ' ' . ($formateur->prenom ?? ''));
            }
        } catch (\Throwable $e) {
            // Silencieux : fallback ci-dessous
        }

        if ($realisationModule->apprenant) {
            return $realisationModule->apprenant->groupes
                ->flatMap->formateurs
                ->map(fn($f) => trim(($f->nom ?? '') . ' ' . ($f->prenom ?? '')))
                ->unique()
                ->join(', ');
        }

        return '';
    }

    /**
     * Génère les en-têtes : 4 lignes PV + ligne vide + ligne titres avec colonnes UA dynamiques.
     */
    public function headings(): array
    {
        $first        = $this->data->first();
        $moduleNom    = '';
        $filiereNom   = '';
        $groupeCode   = '';
        $formateurNom = '';

        if ($first) {
            if ($first->module) {
                $moduleNom  = $first->module->nom ?? '';
                $filiereNom = $first->module->filiere?->nom ?? '';
            }
            if ($first->apprenant) {
                $groupeCode   = $first->apprenant->groupes->pluck('code')->join(', ');
                $formateurNom = $this->resolveFormateur($first);
            }
        }

        // Colonnes UA dynamiques (code UA comme en-tête)
        $uaCodes = $this->getUnitesApprentissage()->map(fn($ua) => $ua->code ?? $ua->reference)->toArray();

        return [
            ['Module :', $moduleNom],
            ['Filière :', $filiereNom],
            ['Groupe :', $groupeCode],
            ['Formateur :', $formateurNom],
            [''],
            array_merge(['Nom', 'Prénom'], $uaCodes, ['Note EFM / 40']),
        ];
    }

    /**
     * Prépare les données : note/40 globale + note CC/20 par UA.
     */
    public function collection()
    {
        $uas = $this->getUnitesApprentissage();

        return $this->data->map(function ($realisationModule) use ($uas) {
            $apprenant = $realisationModule->apprenant;
            $note      = $realisationModule->note_cache ?? 0;
            $bareme    = $realisationModule->bareme_cache ?? 0;
            $noteSur40 = $bareme > 0 ? round(($note / $bareme) * 40, 2) : 0;

            $row = [
                'nom'    => $apprenant ? $apprenant->nom    : '',
                'prenom' => $apprenant ? $apprenant->prenom : '',
            ];

            // Ajouter une colonne par UA (note CC / 20)
            foreach ($uas as $ua) {
                $realisationUa = $this->findRealisationUa($realisationModule, $ua->id);
                $row['ua_' . $ua->id] = $realisationUa ? $this->noteCcSur20($realisationUa) : '';
            }

            // Note EFM / 40 en dernière colonne
            $row['note_efm'] = $noteSur40;

            return $row;
        });
    }

    /**
     * Applique le style et ajoute le tableau légende UA en bas.
     */
    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $lastRow    = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // --- En-têtes PV (lignes 1-4)
        $sheet->getStyle("A1:B1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '1F3864']],
        ]);
        foreach (['A2:B2', 'A3:B3', 'A4:B4'] as $range) {
            $sheet->getStyle($range)->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => '000000']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFF']],
            ]);
        }
        $sheet->getStyle("A1:B4")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']]],
        ]);
        $sheet->getStyle("A1:A4")->applyFromArray(['font' => ['italic' => true]]);

        // --- Ligne 6 : en-têtes de colonnes
        $sheet->getStyle("A6:{$lastColumn}6")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '1F3864']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
        ]);

        // --- Données (ligne 7+) : alternance + bordures
        if ($lastRow >= 7) {
            for ($row = 7; $row <= $lastRow; $row++) {
                $color = ($row % 2 === 0) ? 'DDEEFF' : 'FFFFFF';
                $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $color]],
                ]);
            }
            $sheet->getStyle("A6:{$lastColumn}{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'AAAAAA']]],
            ]);
        }

        // Centrage des colonnes UA et Note EFM (toutes à partir de C)
        foreach (range('C', $lastColumn) as $col) {
            $sheet->getStyle("{$col}1:{$col}{$lastRow}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // --- Tableau légende UA (2 lignes vides + tableau Code/Nom)
        $uas       = $this->getUnitesApprentissage();
        $legendRow = $lastRow + 2;

        // En-tête légende
        $sheet->setCellValue("A{$legendRow}", 'Code UA');
        $sheet->setCellValue("B{$legendRow}", 'Nom de l\'unité d\'apprentissage');
        $sheet->getStyle("A{$legendRow}:B{$legendRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '2E75B6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Lignes UA
        foreach ($uas as $ua) {
            $legendRow++;
            $sheet->setCellValue("A{$legendRow}", $ua->code ?? $ua->reference ?? '');
            $sheet->setCellValue("B{$legendRow}", $ua->nom ?? '');
        }

        // Bordures légende
        $legendStart = $lastRow + 2;
        $sheet->getStyle("A{$legendStart}:B{$legendRow}")->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']]],
        ]);

        // --- Largeur automatique globale
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
