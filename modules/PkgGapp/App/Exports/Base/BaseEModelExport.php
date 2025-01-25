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

class BaseEModelExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'reference',
            'name',
            'table_name',
            'icon',
            'is_pivot_table',
            'description',
            'e_package_id',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($eModel) {
            return [
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

        $sheet->getStyle("A1:Z{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A1:Z1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFD3D3D3',
                ],
            ],
        ]);
    }
}
