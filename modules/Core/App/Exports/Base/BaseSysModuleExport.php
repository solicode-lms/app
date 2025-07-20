<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Exports\Base;

use Modules\Core\Models\SysModule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseSysModuleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'name' => 'name',
                'slug' => 'slug',
                'description' => 'description',
                'is_active' => 'is_active',
                'order' => 'order',
                'version' => 'version',
                'sys_color_reference' => 'sys_color_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'name' => __('Core::sysModule.name'),
                'slug' => __('Core::sysModule.slug'),
                'description' => __('Core::sysModule.description'),
                'is_active' => __('Core::sysModule.is_active'),
                'order' => __('Core::sysModule.order'),
                'version' => __('Core::sysModule.version'),
                'sys_color_reference' => __('Core::sysModule.sys_color_reference'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($sysModule) {
            return [
                'name' => $sysModule->name,
                'slug' => $sysModule->slug,
                'description' => $sysModule->description,
                'is_active' => (string) $sysModule->is_active,
                'order' => (string) $sysModule->order,
                'version' => $sysModule->version,
                'sys_color_reference' => $sysModule->sysColor?->reference,
                'reference' => $sysModule->reference,
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
