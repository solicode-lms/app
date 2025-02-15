<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Exports\Base;

use Modules\PkgGapp\Models\EModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEModelExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'icon' => 'icon',
            'reference' => 'reference',
            'name' => 'name',
            'table_name' => 'table_name',
            'icon' => 'icon',
            'is_pivot_table' => 'is_pivot_table',
            'description' => 'description',
            'e_package_id' => 'e_package_id',
        ];
        }else{
        return [
            'icon' => __('PkgGapp::eModel.icon'),
            'reference' => __('Core::msg.reference'),
            'name' => __('PkgGapp::eModel.name'),
            'table_name' => __('PkgGapp::eModel.table_name'),
            'icon' => __('PkgGapp::eModel.icon'),
            'is_pivot_table' => __('PkgGapp::eModel.is_pivot_table'),
            'description' => __('PkgGapp::eModel.description'),
            'e_package_id' => __('PkgGapp::eModel.e_package_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($eModel) {
            return [
                'icon' => $eModel->icon,
                'reference' => $eModel->reference,
                'name' => $eModel->name,
                'table_name' => $eModel->table_name,
                'icon' => $eModel->icon,
                'is_pivot_table' => $eModel->is_pivot_table,
                'description' => $eModel->description,
                'e_package_id' => $eModel->e_package_id,
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
