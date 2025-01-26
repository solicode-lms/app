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
            'reference',
            'name',
            'type',
            'source_model_id',
            'target_model_id',
            'cascade_on_delete',
            'is_cascade',
            'description',
            'column_name',
            'referenced_table',
            'referenced_column',
            'through',
            'with_column',
            'morph_name',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($eRelationship) {
            return [
                'reference' => $eRelationship->reference,
                'name' => $eRelationship->name,
                'type' => $eRelationship->type,
                'source_model_id' => $eRelationship->source_model_id,
                'target_model_id' => $eRelationship->target_model_id,
                'cascade_on_delete' => $eRelationship->cascade_on_delete,
                'is_cascade' => $eRelationship->is_cascade,
                'description' => $eRelationship->description,
                'column_name' => $eRelationship->column_name,
                'referenced_table' => $eRelationship->referenced_table,
                'referenced_column' => $eRelationship->referenced_column,
                'through' => $eRelationship->through,
                'with_column' => $eRelationship->with_column,
                'morph_name' => $eRelationship->morph_name,
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
