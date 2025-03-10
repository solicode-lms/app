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
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEDataFieldExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'data_type' => 'data_type',
            'column_name' => 'column_name',
            'e_model_id' => 'e_model_id',
            'e_relationship_id' => 'e_relationship_id',
            'field_order' => 'field_order',
            'default_value' => 'default_value',
            'db_primaryKey' => 'db_primaryKey',
            'db_nullable' => 'db_nullable',
            'calculable_sql' => 'calculable_sql',
            'db_unique' => 'db_unique',
            'calculable' => 'calculable',
            'description' => 'description',
        ];
        }else{
        return [
            'reference' => __('Core::msg.reference'),
            'name' => __('PkgGapp::eDataField.name'),
            'data_type' => __('PkgGapp::eDataField.data_type'),
            'column_name' => __('PkgGapp::eDataField.column_name'),
            'e_model_id' => __('PkgGapp::eDataField.e_model_id'),
            'e_relationship_id' => __('PkgGapp::eDataField.e_relationship_id'),
            'field_order' => __('PkgGapp::eDataField.field_order'),
            'default_value' => __('PkgGapp::eDataField.default_value'),
            'db_primaryKey' => __('PkgGapp::eDataField.db_primaryKey'),
            'db_nullable' => __('PkgGapp::eDataField.db_nullable'),
            'calculable_sql' => __('PkgGapp::eDataField.calculable_sql'),
            'db_unique' => __('PkgGapp::eDataField.db_unique'),
            'calculable' => __('PkgGapp::eDataField.calculable'),
            'description' => __('PkgGapp::eDataField.description'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($eDataField) {
            return [
                'reference' => $eDataField->reference,
                'name' => $eDataField->name,
                'data_type' => $eDataField->data_type,
                'column_name' => $eDataField->column_name,
                'e_model_id' => $eDataField->e_model_id,
                'e_relationship_id' => $eDataField->e_relationship_id,
                'field_order' => $eDataField->field_order,
                'default_value' => $eDataField->default_value,
                'db_primaryKey' => $eDataField->db_primaryKey,
                'db_nullable' => $eDataField->db_nullable,
                'calculable_sql' => $eDataField->calculable_sql,
                'db_unique' => $eDataField->db_unique,
                'calculable' => $eDataField->calculable,
                'description' => $eDataField->description,
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
