<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Exports\Base;

use Modules\PkgGapp\Models\MetadataType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseMetadataTypeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'name',
            'code',
            'type',
            'scope',
            'description',
            'is_required',
            'default_value',
            'validation_rules',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($metadataType) {
            return [
                'name' => $metadataType->name,
                'code' => $metadataType->code,
                'type' => $metadataType->type,
                'scope' => $metadataType->scope,
                'description' => $metadataType->description,
                'is_required' => $metadataType->is_required,
                'default_value' => $metadataType->default_value,
                'validation_rules' => $metadataType->validation_rules,
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
