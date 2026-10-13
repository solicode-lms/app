<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\App\Exports\Base;

use Modules\PkgTableauBord\Models\Remediation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRemediationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'realisation_tache_reference' => 'realisation_tache_reference',
                'formateur_reference' => 'formateur_reference',
                'type_remediation' => 'type_remediation',
                'diagnostic' => 'diagnostic',
                'est_resolu' => 'est_resolu',
                'reference' => 'reference',
            ];
        } else {
            return [
                'realisation_tache_reference' => __('PkgRealisationTache::realisationTache.singular'),
                'formateur_reference' => __('PkgFormation::formateur.singular'),
                'type_remediation' => __('PkgTableauBord::remediation.type_remediation'),
                'diagnostic' => __('PkgTableauBord::remediation.diagnostic'),
                'est_resolu' => __('PkgTableauBord::remediation.est_resolu'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($remediation) {
            return [
                'realisation_tache_reference' => $remediation->realisationTache?->reference,
                'formateur_reference' => $remediation->formateur?->reference,
                'type_remediation' => $remediation->type_remediation,
                'diagnostic' => $remediation->diagnostic,
                'est_resolu' => $remediation->est_resolu ? '1' : '0',
                'reference' => $remediation->reference,
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
