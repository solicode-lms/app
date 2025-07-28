<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\WorkflowTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseWorkflowTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'ordre' => 'ordre',
                'code' => 'code',
                'titre' => 'titre',
                'description' => 'description',
                'is_editable_only_by_formateur' => 'is_editable_only_by_formateur',
                'reference' => 'reference',
                'sys_color_reference' => 'sys_color_reference',
            ];
        } else {
            return [
                'ordre' => __('PkgRealisationTache::workflowTache.ordre'),
                'code' => __('PkgRealisationTache::workflowTache.code'),
                'titre' => __('PkgRealisationTache::workflowTache.titre'),
                'description' => __('PkgRealisationTache::workflowTache.description'),
                'is_editable_only_by_formateur' => __('PkgRealisationTache::workflowTache.is_editable_only_by_formateur'),
                'reference' => __('Core::msg.reference'),
                'sys_color_reference' => __('PkgRealisationTache::workflowTache.sys_color_reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($workflowTache) {
            return [
                'ordre' => (string) $workflowTache->ordre,
                'code' => $workflowTache->code,
                'titre' => $workflowTache->titre,
                'description' => $workflowTache->description,
                'is_editable_only_by_formateur' => $workflowTache->is_editable_only_by_formateur,
                'reference' => $workflowTache->reference,
                'sys_color_reference' => $workflowTache->sysColor?->reference,
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
