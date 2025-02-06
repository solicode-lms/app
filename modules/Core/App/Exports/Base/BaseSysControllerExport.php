<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\Core\App\Exports\Base;

use Modules\Core\Models\SysController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseSysControllerExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data,$format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    public function headings(): array
    {
     if($this->format == 'csv'){
        return [
            'sys_module_id' => 'sys_module_id',
            'name' => 'name',
            'slug' => 'slug',
            'description' => 'description',
            'is_active' => 'is_active',
            'reference' => 'reference',
        ];
        }else{
        return [
            'sys_module_id' => __('Core::sysController.sys_module_id'),
            'name' => __('Core::sysController.name'),
            'slug' => __('Core::sysController.slug'),
            'description' => __('Core::sysController.description'),
            'is_active' => __('Core::sysController.is_active'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($sysController) {
            return [
                'sys_module_id' => $sysController->sys_module_id,
                'name' => $sysController->name,
                'slug' => $sysController->slug,
                'description' => $sysController->description,
                'is_active' => $sysController->is_active,
                'reference' => $sysController->reference,
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Appliquer les bordures à toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Appliquer un style spécifique aux en-têtes (ligne 1)
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'], // Texte blanc
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'], // Fond bleu
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Ajuster automatiquement la largeur des colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
