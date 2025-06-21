<?php

namespace Modules\PkgRealisationProjets\App\Exports;

use Maatwebsite\Excel\Concerns\{FromArray, WithHeadings, ShouldAutoSize, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{Border, Fill, Alignment};

class RealisationProjetsPV implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $taches;
    protected $evaluateurs;
    protected $groupe;

    public function __construct(iterable $data, string $format = 'xlsx')
    {
        $this->data = collect($data);
        $this->initTaches();
        $this->initEvaluateurs();
        $this->initGroupe();
    }

    protected function initTaches(): void
    {
        $this->taches = $this->data
            ->flatMap(fn($rp) => $rp->realisationTaches)
            ->pluck('tache')
            ->unique('id')
            ->values();
    }

    protected function initEvaluateurs(): void
    {
        $this->evaluateurs = $this->data
            ->flatMap(fn($rp) => $rp->evaluationRealisationProjets)
            ->pluck('evaluateur')
            ->unique('id')
            ->values();
    }

    protected function initGroupe(): void
    {
        $this->groupe = optional($this->data->first()?->affectationProjet?->groupe);
    }

    /**
     * On retourne un array vide pour gérer les en-têtes personnalisés dans array().
     */
    public function headings(): array
    {
        return [];
    }

    /**
     * Génère toutes les lignes de la feuille : métadonnées, titres, barème, données et évaluateurs.
     */
    public function array(): array
    {
        $rows = [];

        // === MÉTADONNÉES ===
        $rows[] = [''];
        $rows[] = ['Groupe :', $this->groupe->code ?? ''];
        $rows[] = ['Filière :', $this->groupe->filiere->code ?? ''];
        $rows[] = [''];

        // === TITRES ET BARÈME ===
        $rows[] = $this->buildTitlesRow();
        $rows[] = $this->buildBaremeRow();

        // === NOTES PAR APPRENANT ===
        foreach ($this->data as $rp) {
            $rows[] = $this->buildApprenantRow($rp);
        }

        // === SÉPARATEUR ET ÉVALUATEURS ===
        $rows[] = [''];
        $rows[] = ['Évaluateurs :'];
        foreach ($this->evaluateurs as $e) {
            $rows[] = [$e->nom, $e->prenom, '', '', '', ''];
        }

        return $rows;
    }

    protected function buildTitlesRow(): array
    {
        $cols = ['', ''];
        foreach ($this->taches as $index => $tache) {
            $cols[] = 'Q' . ($index + 1);
        }
        $cols[] = 'Total';

        return $cols;
    }

    protected function buildBaremeRow(): array
    {
        // Deux premières colonnes vides pour éviter la répétition
        $row = ['Nom', 'Prénom'];
        foreach ($this->taches as $tache) {
            $row[] = number_format($tache->note ?? 0, 2, '.', '');
        }

        // Calcul de la somme des barèmes
        $totalBareme = $this->taches
        ->reduce(fn($carry, $tache) => $carry + ($tache->note ?? 0), 0);
         // Ajout du total formaté
        $row[] = number_format($totalBareme, 2, '.', '');

        return $row;
    }

    protected function buildApprenantRow($rp): array
    {
        $row = [
            $rp->apprenant->nom ?? '',
            $rp->apprenant->prenom ?? '',
        ];

        $total = 0;
        foreach ($this->taches as $tache) {
            $rt = $rp->realisationTaches->firstWhere('tache_id', $tache->id);
            $note = $rt?->note !== null ? number_format($rt->note, 2, '.', '') : '';
            $row[] = $note;
            $total += is_numeric($note) ? (float) $note : 0;
        }

        $row[] = number_format($total, 2, '.', '');
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $this->styleMetadata($sheet);
        $this->styleHeaders($sheet);
        $this->styleNotes($sheet);
        $this->styleEvaluateurs($sheet);

        // Configuration de la page (paysage, ajustement largeur)
        $sheet->getPageSetup()
              ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
              ->setFitToWidth(1)
              ->setFitToHeight(0);
    }

    protected function styleMetadata(Worksheet $sheet): void
    {
        $range = 'A2:B3';
        $sheet->getStyle($range)
              ->applyFromArray($this->commonStyle([
                  'font' => ['bold' => true],
                  'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F2F2F2']],
              ]));

        foreach ([2, 3] as $row) {
            $sheet->getRowDimension($row)->setRowHeight(20);
        }
    }

    protected function styleHeaders(Worksheet $sheet): void
    {
        $lastCol = $sheet->getHighestColumn();
        $headerRanges = ['C5:' . $lastCol . '5', 'A6:' . $lastCol . '6'];
        foreach ($headerRanges as $range) {
            $sheet->getStyle($range)
                  ->applyFromArray($this->commonStyle([
                      'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
                      'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4472C4']],
                      'alignment' => [
                          'horizontal' => Alignment::HORIZONTAL_CENTER,
                          'vertical'   => Alignment::VERTICAL_CENTER,
                      ],
                  ]));
        }
    }

    protected function styleNotes(Worksheet $sheet): void
    {
        $start = 7;
        $end   = $start + $this->data->count() - 1;
        $lastCol = $sheet->getHighestColumn();

        $sheet->getStyle("A{$start}:B{$end}")
              ->applyFromArray($this->commonStyle([    
                  'alignment' => [
                      'vertical'   => Alignment::VERTICAL_CENTER,
                  ],
              ]));

        $sheet->getStyle("C{$start}:{$lastCol}{$end}")
              ->applyFromArray($this->commonStyle([
                  'alignment' => [
                      'horizontal' => Alignment::HORIZONTAL_CENTER,
                      'vertical'   => Alignment::VERTICAL_CENTER,
                  ],
              ]));
    }

    protected function styleEvaluateurs(Worksheet $sheet): void
    {
        $start = 7 + $this->data->count() + 2;
        $end   = $start + $this->evaluateurs->count() - 1;

        $sheet->getStyle("A{$start}:F{$end}")
              ->applyFromArray($this->commonStyle([
                  'font' => ['italic' => true],
              ]));

        for ($i = $start; $i <= $end; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
            $sheet->mergeCells("C{$i}:F{$i}");
        }
    }

    /**
     * Style commun pour bordures, fusionnable avec des styles spécifiques.
     */
    protected function commonStyle(array $extra = []): array
    {
        $base = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => '000000'],
                ],
            ],
        ];

        return array_merge_recursive($base, $extra);
    }
}
