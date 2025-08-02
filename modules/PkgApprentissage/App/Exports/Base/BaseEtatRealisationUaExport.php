<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\EtatRealisationUa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEtatRealisationUaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'nom' => 'nom',
                'code' => 'code',
                'sys_color_reference' => 'sys_color_reference',
                'is_editable_only_by_formateur' => 'is_editable_only_by_formateur',
                'description' => 'description',
                'reference' => 'reference',
            ];
        } else {
            return [
                'ordre' => __('PkgApprentissage::etatRealisationUa.ordre'),
                'nom' => __('PkgApprentissage::etatRealisationUa.nom'),
                'code' => __('PkgApprentissage::etatRealisationUa.code'),
                'sys_color_reference' => __('PkgApprentissage::etatRealisationUa.sys_color_reference'),
                'is_editable_only_by_formateur' => __('PkgApprentissage::etatRealisationUa.is_editable_only_by_formateur'),
                'description' => __('PkgApprentissage::etatRealisationUa.description'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($etatRealisationUa) {
            return [
                'ordre' => (string) $etatRealisationUa->ordre,
                'nom' => $etatRealisationUa->nom,
                'code' => $etatRealisationUa->code,
                'sys_color_reference' => $etatRealisationUa->sysColor?->reference,
                'is_editable_only_by_formateur' => $etatRealisationUa->is_editable_only_by_formateur ? '1' : '0',
                'description' => $etatRealisationUa->description,
                'reference' => $etatRealisationUa->reference,
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
