<?php

namespace Modules\PkgApprentissage\App\Exports;

use Modules\PkgApprentissage\App\Exports\Base\BaseRealisationModuleExport;

class RealisationModuleExport extends BaseRealisationModuleExport {
    
    /**
     * Résout le formateur (nom + prénom) via le dernier projet évalué.
     * Fallback sur les formateurs du groupe.
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

        // Fallback : formateur du groupe
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
     * Génère les en-têtes du fichier exporté (4 lignes PV + ligne titres)
     */
    public function headings(): array
    {
        $first = $this->data->first();
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

        return [
            ['Module :', $moduleNom],
            ['Filière :', $filiereNom],
            ['Groupe :', $groupeCode],
            ['Formateur :', $formateurNom],
            [''],
            ['Nom', 'Prénom', 'Note / 40']
        ];
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationModule) {
            $apprenant = $realisationModule->apprenant;
            $note = $realisationModule->note_cache ?? 0;
            $bareme = $realisationModule->bareme_cache ?? 0;
            $noteSur40 = $bareme > 0 ? round(($note / $bareme) * 40, 2) : 0;
            
            return [
                'nom'    => $apprenant ? $apprenant->nom : '',
                'prenom' => $apprenant ? $apprenant->prenom : '',
                'note'   => $noteSur40,
            ];
        });
    }

    /**
     * Applique le style au fichier exporté (format PV)
     */
    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $lastRow    = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // --- Ligne 1 : Module (fond bleu foncé, texte blanc)
        $sheet->getStyle("A1:B1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '1F3864']],
        ]);

        // --- Ligne 2 : Filière (fond blanc, texte noir)
        $sheet->getStyle("A2:B2")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => '000000']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFF']],
        ]);

        // --- Ligne 3 : Groupe (fond blanc, texte noir)
        $sheet->getStyle("A3:B3")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => '000000']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFF']],
        ]);

        // --- Ligne 4 : Formateur (fond blanc, texte noir)
        $sheet->getStyle("A4:B4")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => '000000']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFFF']],
        ]);

        // --- Bordure noire sur tout l'en-tête du PV (A1:B4)
        $sheet->getStyle("A1:B4")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
        ]);

        // --- Labels A1:A4 en italique aussi
        $sheet->getStyle("A1:A4")->applyFromArray([
            'font' => ['italic' => true],
        ]);

        // --- Ligne 6 : En-têtes des colonnes du tableau
        $sheet->getStyle("A6:{$lastColumn}6")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => '1F3864']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // --- Données du tableau (ligne 7 et après) avec bordures et alternance de couleur
        if ($lastRow >= 7) {
            for ($row = 7; $row <= $lastRow; $row++) {
                $color = ($row % 2 === 0) ? 'DDEEFF' : 'FFFFFF';
                $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => $color]],
                ]);
            }
            $sheet->getStyle("A6:{$lastColumn}{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color'       => ['argb' => 'AAAAAA'],
                    ],
                ],
            ]);
        }

        // --- Largeur automatique
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // --- Centrage de la colonne Note (colonne C)
        $sheet->getStyle("C1:C{$lastRow}")->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

}
