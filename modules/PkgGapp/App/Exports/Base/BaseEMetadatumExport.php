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
            'Value',
            'reference',
            'value_boolean',
            'value_string',
            'value_integer',
            'value_float',
            'value_date',
            'value_datetime',
            'value_enum',
            'value_json',
            'value_text',
            'e_model_id',
            'e_data_field_id',
            'e_metadata_definition_id',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($eMetadatum) {
            return [
                'Value' => $eMetadatum->Value,
                'reference' => $eMetadatum->reference,
                'value_boolean' => $eMetadatum->value_boolean,
                'value_string' => $eMetadatum->value_string,
                'value_integer' => $eMetadatum->value_integer,
                'value_float' => $eMetadatum->value_float,
                'value_date' => $eMetadatum->value_date,
                'value_datetime' => $eMetadatum->value_datetime,
                'value_enum' => $eMetadatum->value_enum,
                'value_json' => $eMetadatum->value_json,
                'value_text' => $eMetadatum->value_text,
                'e_model_id' => $eMetadatum->e_model_id,
                'e_data_field_id' => $eMetadatum->e_data_field_id,
                'e_metadata_definition_id' => $eMetadatum->e_metadata_definition_id,
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
