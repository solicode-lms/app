<?php
namespace Modules\PkgRealisationProjets\App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RealisationProjetsPV implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $format;
    protected $taches;
    protected $evaluateurs;
    protected $groupe;

    public function __construct($data, $format = 'xlsx')
    {
        $this->data = $data;
        $this->format = $format;

        $this->taches = collect($this->data)
            ->flatMap(fn($rp) => $rp->realisationTaches)
            ->pluck('tache')
            ->unique('id')
            ->values();

        $this->evaluateurs = collect($this->data)
            ->flatMap(fn($rp) => $rp->evaluationRealisationProjets)
            ->pluck('evaluateur')
            ->unique('id')
            ->values();

        $this->groupe = optional($this->data->first()?->affectationProjet?->groupe);
    }


    public function headings(): array
    {
        return [
        ];
   
    }
    public function titles(): array
    {
        $base = ['',''];
        $questions = [];
        $number = 1;
        foreach ($this->taches as $tache) {
            $questions[] = 'Q' . $number;
            $number++;
        }
        $questions[] = 'Total';
        return array_merge($base, $questions);
    }

    public function array(): array
    {
        $rows = [];

        // === MÉTADONNÉES ===
        $rows[] = [''];
        $rows[] = ['Groupe :', $this->groupe->code ?? ''];
        $rows[] = ['Filière :', $this->groupe->filiere->code ?? ''];
        $rows[] = [''];

        // === BARÈME ===
        $rows[] = $this->titles();
        $barre = ['Nom', 'Prénom'];
        foreach ($this->taches as $tache) {
            $barre[] = number_format($tache->note ?? 0, 2, '.', '');
        }
        $barre[] = '';
        $rows[] = $barre;
       

        // === NOTES PAR APPRENANT ===
        foreach ($this->data as $realisationProjet) {
            $row = [];
            $row[] = $realisationProjet->apprenant->nom ?? '';
            $row[] = $realisationProjet->apprenant->prenom ?? '';

            $total = 0;
            foreach ($this->taches as $tache) {
                $rt = $realisationProjet->realisationTaches->firstWhere('tache_id', $tache->id);
                $note = $rt?->note !== null ? number_format($rt->note, 2, '.', '') : '';
                $row[] = $note;
                if ($note !== '') {
                    $total += (float) $note;
                }
            }

            $row[] = number_format($total, 2, '.', '');
            $rows[] = $row;
        }

        // === SÉPARATEUR ===
        $rows[] = [''];

        // === ÉVALUATEURS ===
        $rows[] = ['Évaluateurs :'];
        foreach ($this->evaluateurs as $e) {
            $rows[] = [$e->nom,$e->prenom];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = $sheet->getHighestColumn();

        // === Style des métadonnées (1 à 2) ===
        $sheet->getStyle("A2:B3")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F2F2F2']],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            ]
        ]);
         // Définir la hauteur des lignes de métadonnées
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // === Style des entêtes de note (ligne 5) ===
        $headerRow_1 = 5;
        $last_headerRow_1 = 5;
        $headerRow_2 = 6;
        $last_headerRow_2 = 6;
        $sheet->getStyle("C{$headerRow_1}:{$lastColumn}{$last_headerRow_1}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4472C4']],
             'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
         $sheet->getStyle("A{$headerRow_2}:{$lastColumn}{$last_headerRow_2}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4472C4']],
             'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // === Style des données de notes (barème + lignes apprenants) ===
        $noteStartRow = 7; // barème
        $noteEndRow = $noteStartRow + $this->data->count() - 1;
        $sheet->getStyle("A{$noteStartRow}:B{$noteEndRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
         $sheet->getStyle("C{$noteStartRow}:{$lastColumn}{$noteEndRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ], 'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // === Style des évaluateurs ===
        $evaluateurStart = $noteEndRow + 3;
        $evaluateurEnd = $evaluateurStart + count($this->evaluateurs) -1;
        $sheet->getStyle("A{$evaluateurStart}:F{$evaluateurEnd}")->applyFromArray([
            'font' => ['italic' => true],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);
        // Définir la hauteur pour chaque ligne d'évaluateur
        // Définir la hauteur et fusionner les colonnes pour chaque ligne d'évaluateur
                for ($i = $evaluateurStart; $i <= $evaluateurEnd; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                    $sheet->mergeCells("C{$i}:F{$i}"); // Espace signature
                }
        // === Configuration de la page pour impression paysage et ajustement à une page ===
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0); // 0 = automatique (ne force pas sur une seule page en hauteur)
    }
}
