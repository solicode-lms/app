<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Exports\Base;

use Modules\PkgGapp\Models\EDataField;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseEDataFieldExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'code',
            'name',
            'column_name',
            'data_type',
            'db_nullable',
            'db_primaryKey',
            'db_unique',
            'default_value',
            'description',
            'e_model_id',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($eDataField) {
            return [
                'code' => $eDataField->code,
                'name' => $eDataField->name,
                'column_name' => $eDataField->column_name,
                'data_type' => $eDataField->data_type,
                'db_nullable' => $eDataField->db_nullable,
                'db_primaryKey' => $eDataField->db_primaryKey,
                'db_unique' => $eDataField->db_unique,
                'default_value' => $eDataField->default_value,
                'description' => $eDataField->description,
                'e_model_id' => $eDataField->e_model_id,
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
