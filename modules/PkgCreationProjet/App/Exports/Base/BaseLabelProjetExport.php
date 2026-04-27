<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Exports\Base;

use Modules\PkgCreationProjet\Models\LabelProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseLabelProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $format;

    public function __construct($data, $format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    /**
     * Génère les en-têtes du fichier exporté
     */
    public function headings(): array
    {
        if ($this->format === 'csv') {
            return [
                'nom' => 'nom',
                'description' => 'description',
                'projet_reference' => 'projet_reference',
                'sys_color_reference' => 'sys_color_reference',
                'reference' => 'reference',
                'realisationTaches' => 'realisationTaches',
                'taches' => 'taches',
            ];
        } else {
            return [
                'nom' => __('PkgCreationProjet::labelProjet.nom'),
                'description' => __('PkgCreationProjet::labelProjet.description'),
                'projet_reference' => __('PkgCreationProjet::projet.singular'),
                'sys_color_reference' => __('Core::sysColor.singular'),
                'reference' => __('Core::msg.reference'),
                    'realisationTaches' => __('PkgRealisationTache::realisationTache.plural'),
                    'taches' => __('PkgCreationTache::tache.plural'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($labelProjet) {
            return [
                'nom' => $labelProjet->nom,
                'description' => $labelProjet->description,
                'projet_reference' => $labelProjet->projet?->reference,
                'sys_color_reference' => $labelProjet->sysColor?->reference,
                'reference' => $labelProjet->reference,
                'realisationTaches' => $labelProjet->realisationTaches
                    ->pluck('reference')
                    ->implode('|'),
                'taches' => $labelProjet->taches
                    ->pluck('reference')
                    ->implode('|'),
            ];
        });
    }

    /**
     * Applique le style au fichier exporté
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Bordures pour toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Style spécifique pour les en-têtes
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Largeur automatique pour toutes les colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
