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
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseERelationshipExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'reference' => 'reference',
            'name' => 'name',
            'type' => 'type',
            'source_e_model_id' => 'source_e_model_id',
            'target_e_model_id' => 'target_e_model_id',
            'cascade_on_delete' => 'cascade_on_delete',
            'is_cascade' => 'is_cascade',
            'description' => 'description',
            'column_name' => 'column_name',
            'referenced_table' => 'referenced_table',
            'referenced_column' => 'referenced_column',
            'through' => 'through',
            'with_column' => 'with_column',
            'morph_name' => 'morph_name',
        ];
        }else{
        return [
            'reference' => __('Core::msg.reference'),
            'name' => __('PkgGapp::eRelationship.name'),
            'type' => __('PkgGapp::eRelationship.type'),
            'source_e_model_id' => __('PkgGapp::eRelationship.source_e_model_id'),
            'target_e_model_id' => __('PkgGapp::eRelationship.target_e_model_id'),
            'cascade_on_delete' => __('PkgGapp::eRelationship.cascade_on_delete'),
            'is_cascade' => __('PkgGapp::eRelationship.is_cascade'),
            'description' => __('PkgGapp::eRelationship.description'),
            'column_name' => __('PkgGapp::eRelationship.column_name'),
            'referenced_table' => __('PkgGapp::eRelationship.referenced_table'),
            'referenced_column' => __('PkgGapp::eRelationship.referenced_column'),
            'through' => __('PkgGapp::eRelationship.through'),
            'with_column' => __('PkgGapp::eRelationship.with_column'),
            'morph_name' => __('PkgGapp::eRelationship.morph_name'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($eRelationship) {
            return [
                'reference' => $eRelationship->reference,
                'name' => $eRelationship->name,
                'type' => $eRelationship->type,
                'source_e_model_id' => $eRelationship->source_e_model_id,
                'target_e_model_id' => $eRelationship->target_e_model_id,
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
