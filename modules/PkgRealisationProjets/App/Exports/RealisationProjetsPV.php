<?php

namespace Modules\PkgRealisationProjets\App\Exports;

use Maatwebsite\Excel\Concerns\{FromArray, WithHeadings, ShouldAutoSize, WithStyles};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\{Border, Fill, Alignment};

class RealisationProjetsPV implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $taches;
    protected $evaluateurs;
    protected $groupe;
    protected $formateur;
    protected int $metadataSpan = 2;

    public function __construct(iterable $data, string $format = 'xlsx')
    {
        $this->data = collect($data);
        $this->initTaches();
        $this->initEvaluateurs();
        $this->initGroupe();
        $this->initFormateur();
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

    protected function initFormateur(): void
    {
        $this->formateur = optional($this->data->first()?->affectationProjet?->projet?->formateur);
    }

    public function headings(): array
    {
        return [];
    }

    public function array(): array
    {
        $rows = [];

        // MÉTADONNÉES
        $rows[] = [''];
        $rows[] = ['Groupe :', $this->groupe->code ?? ''];
        $rows[] = ['Filière :', $this->groupe->filiere->code ?? ''];

        // ÉVALUATEURS / FORMATEUR
        if ($this->evaluateurs->isNotEmpty()) {
            $names = $this->evaluateurs->map(fn($e) => trim($e->nom . ' ' . $e->prenom))->join(', ');
            $label = 'Évaluateurs :';
            $text  = $names;
        } else {
            $names = trim($this->formateur->nom . ' ' . $this->formateur->prenom);
            $label = 'Formateur :';
            $text  = $names;
        }
        $length      = mb_strlen($text);
        $charsPerCol = 7;
        $span        = max(2, (int) ceil($length / $charsPerCol));
        $this->metadataSpan = $span;
        $rows[] = array_merge([$label, $text], array_fill(0, $span - 2, ''));
        $rows[] = [''];

        // TITRES ET BARÈME
        $rows[] = $this->buildTitlesRow();
        $rows[] = $this->buildBaremeRow();

        // NOTES PAR APPRENANT
        foreach ($this->data as $rp) {
            $rows[] = $this->buildApprenantRow($rp);
        }

        // SÉPARATEUR ET SIGNATURE
        $rows[] = [''];
        if ($this->evaluateurs->isNotEmpty()) {
            $rows[] = ['Évaluateurs :'];
            foreach ($this->evaluateurs as $e) {
                $rows[] = array_merge(
                    [$e->nom, $e->prenom],
                    array_fill(0, count($this->taches) + 1 + ($this->hasEchelle() ? 1 : 0), '')
                );
            }
        } else {
            $rows[] = ['Formateur :'];
            $rows[] = [$this->formateur->nom ?? '', $this->formateur->prenom ?? ''];
        }

        return $rows;
    }

    protected function buildTitlesRow(): array
    {
        $cols = ['', ''];
        foreach ($this->taches as $i => $tache) {
            $cols[] = 'Q' . ($i + 1);
        }
        $cols[] = 'Total';
        $cols[] = $this->hasEchelle() ? 'Note'  : '';
        return $cols;
    }

    protected function buildBaremeRow(): array
    {
        $row = ['Nom', 'Prénom'];
        foreach ($this->taches as $tache) {
            $row[] = number_format($tache->note ?? 0, 2, '.', '');
        }
        $sum = $this->taches->reduce(fn($carry, $t) => $carry + ($t->note ?? 0), 0);
        $row[] = number_format($sum, 2, '.', '');
        $row[] = $this->hasEchelle() ? number_format($this->data->first()->affectationProjet->echelle_note_cible, 0, '', '') : '';
        return $row;
    }

    protected function buildApprenantRow($rp): array
    {
        $row   = [$rp->apprenant->nom ?? '', $rp->apprenant->prenom ?? ''];
        $total = 0;
        foreach ($this->taches as $tache) {
            $rt   = $rp->realisationTaches->firstWhere('tache_id', $tache->id);
            $note = $rt?->note ?? 0;
            $row[] = $note !== 0 ? number_format($note, 2, '.', '') : '';
            $total += $note;
        }
        $row[] = number_format($total, 2, '.', '');
        $row[] = $this->hasEchelle() ? number_format($rp->calculerNoteAvecEchelle(), 2, '.', '') : '';
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->freezePane('C8');
        $this->styleMetadata($sheet);
        $this->styleHeaders($sheet);
        $this->styleNotes($sheet);
        $this->styleEvaluateurs($sheet);

        $sheet->getPageSetup()
              ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
              ->setFitToWidth(1)
              ->setFitToHeight(0);
    }

    protected function hasEchelle(): bool
    {
        $e = $this->data->first()?->affectationProjet->echelle_note_cible ?? null;
        return is_numeric($e) && $e > 0;
    }

    protected function styleMetadata(Worksheet $sheet): void
    {
        $endCol = Coordinate::stringFromColumnIndex(1 + $this->metadataSpan);
        $sheet->mergeCells("B2:{$endCol}2");
        $sheet->mergeCells("B3:{$endCol}3");
        $sheet->getStyle("A2:{$endCol}3")->applyFromArray($this->commonStyle([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F2F2F2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]));
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(20);

        $evalEnd = Coordinate::stringFromColumnIndex(1 + $this->metadataSpan);
        $sheet->mergeCells("B4:{$evalEnd}4");
        $sheet->getStyle("A4:{$evalEnd}4")->applyFromArray($this->commonStyle([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'F2F2F2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]));
        $sheet->getRowDimension(4)->setRowHeight(20);
    }

    protected function styleHeaders(Worksheet $sheet): void
    {
        $totalCols = count($this->taches) + 1 + ($this->hasEchelle() ? 1 : 0);
        $lastCol   = Coordinate::stringFromColumnIndex(2 + $totalCols);
        foreach (['C6:'.$lastCol.'6', 'A7:'.$lastCol.'7'] as $range) {
            $sheet->getStyle($range)->applyFromArray($this->commonStyle([
                'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4472C4']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ]));
        }
    }

    protected function styleNotes(Worksheet $sheet): void
    {
        $totalCols = count($this->taches) + 1 + ($this->hasEchelle() ? 1 : 0);
        $lastCol   = Coordinate::stringFromColumnIndex(2 + $totalCols);
        $start     = 8;
        $end       = $start + $this->data->count() - 1;
        for ($r = $start; $r <= $end; $r++) {
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:{$lastCol}{$r}")
                      ->getFill()
                      ->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()
                      ->setARGB('F9FBFD');
            }
        }
        $sheet->getStyle("A{$start}:B{$end}")->applyFromArray($this->commonStyle([
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ]));
        $sheet->getStyle("C{$start}:{$lastCol}{$end}")->applyFromArray($this->commonStyle([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
        ]));
    }

    protected function styleEvaluateurs(Worksheet $sheet): void
    {
        $start = 9 + $this->data->count() + 1;
        // Assurer au moins une ligne (formateur) si aucun évaluateur
        $end = $start + max(1, $this->evaluateurs->count()) - 1;
        $lastCol = 'G';

        // Style italique et hauteur de ligne
        $sheet->getStyle("A{$start}:{$lastCol}{$end}")
              ->applyFromArray($this->commonStyle(['font' => ['italic' => true]]));
        for ($i = $start; $i <= $end; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
            // Deux colonnes pour Nom et Prénom restent individuelles (A et B)
            // Fusionner les 5 colonnes suivantes (C à G) pour la zone de signature
            $sheet->mergeCells("C{$i}:G{$i}");
        }
    }

    protected function commonStyle(array $extra = []): array
    {
        $base = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']]]];
        return array_merge_recursive($base, $extra);
    }
}
