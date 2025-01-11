<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Exports\Base;

use Modules\PkgGapp\Models\ERelationship;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseERelationshipExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'source_model_id',
            'target_model_id',
            'type',
            'source_field',
            'target_field',
            'cascade_on_delete',
            'description',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($eRelationship) {
            return [
                'source_model_id' => $eRelationship->source_model_id,
                'target_model_id' => $eRelationship->target_model_id,
                'type' => $eRelationship->type,
                'source_field' => $eRelationship->source_field,
                'target_field' => $eRelationship->target_field,
                'cascade_on_delete' => $eRelationship->cascade_on_delete,
                'description' => $eRelationship->description,
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
