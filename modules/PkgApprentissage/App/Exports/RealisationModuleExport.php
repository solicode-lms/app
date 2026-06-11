<?php

namespace Modules\PkgApprentissage\App\Exports;

use Modules\PkgApprentissage\App\Exports\Base\BaseRealisationModuleExport;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class RealisationModuleExport extends BaseRealisationModuleExport implements WithStrictNullComparison, WithTitle
{
    public function title(): string
    {
        $first = $this->data->first();
        if ($first && $first->module) {
            // Nettoyer les caractères interdits pour les noms d'onglets Excel (comme \ / ? * : [ ])
            $title = str_replace(['\\', '/', '?', '*', ':', '[', ']'], '', $first->module->nom);
            return substr($title, 0, 31);
        }
        return 'PV';
    }
    public function __construct($data, $format)
    {
        // Trier les données par nom puis par prénom d'apprenant
        $sortedData = $data->sort(function ($a, $b) {
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

        parent::__construct($sortedData, $format);
    }
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
     * Répartit les unités d'apprentissage en 2 ou 3 groupes (CC1, CC2, CC3).
     * @return array Tableau de collections d'UAs
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

        // Colonnes UA dynamiques et CC intercalés
        $ccGroups = $this->getCcGroups();
        $dynamicHeadings = [];

        foreach ($ccGroups as $index => $groupUas) {
            $ccNum = $index + 1;
            // Ajouter les en-têtes d'UA pour ce groupe
            foreach ($groupUas as $ua) {
                $dynamicHeadings[] = ($ua->code ?? $ua->reference) . " / 20";
            }
            // Ajouter la colonne CC associée avec le barème / 20
            $dynamicHeadings[] = "CC" . $ccNum . " / 20";
        }

        // Afficher la colonne "Reste à évaluer" seulement si nécessaire
        $hasEvaluationsMissing = $this->data->contains(fn($rm) => ($rm->bareme_non_evalue_cache ?? 0) > 0);
        $extraHeadings = ['Moy CC / 20', 'EFM / 40', 'Note / 20'];

        if ($hasEvaluationsMissing) {
            $extraHeadings[] = 'Reste à évaluer';
        }

        return [
            ['Module :', $moduleNom],
            ['Filière :', $filiereNom],
            ['Groupe :', $groupeCode],
            ['Formateur :', $formateurNom],
            [''],
            array_merge(['Nom', 'Prénom'], $dynamicHeadings, $extraHeadings),
        ];
    }

    /**
     * Prépare les données : note/40 globale + note CC/20 par UA.
     */
    public function collection()
    {
        $ccGroups = $this->getCcGroups();

        return $this->data->map(function ($realisationModule) use ($ccGroups) {
            $apprenant = $realisationModule->apprenant;
            $note      = $realisationModule->note_cache ?? 0;
            $bareme    = $realisationModule->bareme_cache ?? 0;
            $noteSur40 = $bareme > 0 ? round(($note / $bareme) * 40, 2) : 0;

            $row = [
                'nom'    => $apprenant ? $apprenant->nom    : '',
                'prenom' => $apprenant ? $apprenant->prenom : '',
            ];

            $totalCc = 0;
            $countCc = 0;

            // Parcourir chaque groupe de CC pour insérer les notes d'UA et calculer le CC associé
            foreach ($ccGroups as $index => $groupUas) {
                $ccNum = $index + 1;
                $totalCcGroup = 0;
                $countCcGroup = 0;

                foreach ($groupUas as $ua) {
                    $realisationUa = $this->findRealisationUa($realisationModule, $ua->id);
                    $isNoted = $realisationUa && ($realisationUa->bareme_cc_cache ?? 0) > 0;

                    if ($isNoted) {
                        $noteCc = $this->noteCcSur20($realisationUa);
                        $row['ua_' . $ua->id] = $noteCc;

                        $totalCcGroup += $noteCc;
                        $countCcGroup++;

                        $totalCc += $noteCc;
                        $countCc++;
                    } else {
                        // L'UA sans note s'affiche vide et n'entre pas dans le calcul
                        $row['ua_' . $ua->id] = '';
                    }
                }

                // Calculer le CC (moyenne des UAs participantes)
                $row['cc_' . $ccNum] = $countCcGroup > 0 ? round($totalCcGroup / $countCcGroup, 2) : '';
            }

            // Moyenne CC / 20
            $moyenneCc = $countCc > 0 ? round($totalCc / $countCc, 2) : '';
            $row['moyenne_cc'] = $moyenneCc;

            // Note EFM / 40
            $row['note_efm'] = $noteSur40;

            // Note / 20 (Note de Module)
            // Le total est sur 60 (40 + 20), on divise par 3 pour ramener sur 20
            $noteModuleSur20 = round(($noteSur40 + ($moyenneCc !== '' ? $moyenneCc : 0)) / 3, 2);
            $row['note_module'] = $noteModuleSur20;

            // Reste à évaluer seulement si nécessaire
            $hasEvaluationsMissing = $this->data->contains(fn($rm) => ($rm->bareme_non_evalue_cache ?? 0) > 0);
            if ($hasEvaluationsMissing) {
                $row['reste_a_evaluer'] = $realisationModule->bareme_non_evalue_cache ?? 0;
            }

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
        $hasEvaluationsMissing = $this->data->contains(fn($rm) => ($rm->bareme_non_evalue_cache ?? 0) > 0);

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

        // Calcul des lettres de colonnes pour les CC et EFM
        $uas = $this->getUnitesApprentissage();
        $ccGroups = $this->getCcGroups();
        $ccColLetters = [];
        $colIndex = 3; // Commence à C

        foreach ($ccGroups as $groupUas) {
            $colIndex += $groupUas->count();
            $ccColLetters[] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $colIndex++;
        }

        $numCcGroups = count($ccGroups);
        $numUas = $uas->count();
        $totalDynamicCols = $numUas + $numCcGroups;
        $efmColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + $totalDynamicCols + 1);

        // --- Données (ligne 7+) : alternance + bordures + rouge doux si reste à évaluer
        if ($lastRow >= 7) {
            $dataIndex = 0;
            for ($row = 7; $row <= $lastRow; $row++) {
                $realisationModule = $this->data->values()->get($dataIndex);
                $hasMissing = $realisationModule && ($realisationModule->bareme_non_evalue_cache ?? 0) > 0;

                // Style par défaut ou Rouge doux
                if ($hasMissing) {
                    $color = 'FFD9D9'; // Rouge doux/pastel
                } else {
                    $color = ($row % 2 === 0) ? 'DDEEFF' : 'FFFFFF';
                }

                $sheet->getStyle("A{$row}:{$lastColumn}{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $color]],
                ]);

                // Surcharger la couleur des cellules pour les colonnes de CC
                foreach ($ccColLetters as $col) {
                    $sheet->getStyle("{$col}{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'E2EFDA']], // Vert d'eau très doux
                    ]);
                }

                // Surcharger la couleur de la cellule pour la colonne EFM
                $sheet->getStyle("{$efmColLetter}{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FCE4D6']], // Pêche très doux
                ]);

                $dataIndex++;
            }
            $sheet->getStyle("A6:{$lastColumn}{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'AAAAAA']]],
            ]);
        }

        // Colorer les cellules d'en-tête (ligne 6) pour CC et EFM
        foreach ($ccColLetters as $col) {
            $sheet->getStyle("{$col}6")->applyFromArray([
                'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => '000000']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'C6E0B4']], // Vert d'eau un peu plus prononcé
            ]);
        }
        $sheet->getStyle("{$efmColLetter}6")->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['argb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F8CBAD']], // Pêche un peu plus prononcé
        ]);

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

        // --- Largeur des colonnes
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        
        $ccGroups = $this->getCcGroups();
        $numCcGroups = count($ccGroups);
        $numUas = $uas->count();
        $totalDynamicCols = $numUas + $numCcGroups;

        // Colonnes UA et CC dynamiques : Largeur fixe (12)
        for ($i = 0; $i < $totalDynamicCols; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(3 + $i);
            $sheet->getColumnDimension($col)->setAutoSize(false);
            $sheet->getColumnDimension($col)->setWidth(12);
        }

        // Colonnes suivantes : Moy CC, EFM, Note / 20, Reste à évaluer
        $colIndex = 3 + $totalDynamicCols; // Commence juste après les colonnes UA + CC

        // Moy CC / 20
        $moyCcCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
        $sheet->getColumnDimension($moyCcCol)->setAutoSize(true);

        // EFM / 40
        $efmCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
        $sheet->getColumnDimension($efmCol)->setWidth(12);

        // Note / 20
        $noteCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex++);
        $sheet->getColumnDimension($noteCol)->setWidth(12);

        // Colonne Reste à évaluer si présente
        if ($hasEvaluationsMissing) {
            $resteCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->getStyle($resteCol . "1:" . $resteCol . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getColumnDimension($resteCol)->setWidth(15);
        }
    }
}
