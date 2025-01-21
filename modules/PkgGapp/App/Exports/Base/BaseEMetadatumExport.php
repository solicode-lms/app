<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Exports\Base;

use Modules\PkgGapp\Models\EMetadatum;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseEMetadatumExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'value_boolean',
            'value_string',
            'value_int',
            'value_object',
            'object_id',
            'object_type',
            'e_metadata_definition_id',
            'EModel',
            'EDataField',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($eMetadatum) {
            return [
                'code' => $eMetadatum->code,
                'value_boolean' => $eMetadatum->value_boolean,
                'value_string' => $eMetadatum->value_string,
                'value_int' => $eMetadatum->value_int,
                'value_object' => $eMetadatum->value_object,
                'object_id' => $eMetadatum->object_id,
                'object_type' => $eMetadatum->object_type,
                'e_metadata_definition_id' => $eMetadatum->e_metadata_definition_id,
                'EModel' => $eMetadatum->EModel,
                'EDataField' => $eMetadatum->EDataField,
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
