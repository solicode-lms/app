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
    protected $format;

    public function __construct($data, $format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    /**
     * Génère les en-têtes du fichier exporté
     */
    public function headings(): array
    {
        if ($this->format === 'csv') {
            return [
                'name' => 'name',
                'e_model_reference' => 'e_model_reference',
                'data_type' => 'data_type',
                'default_value' => 'default_value',
                'column_name' => 'column_name',
                'e_relationship_reference' => 'e_relationship_reference',
                'field_order' => 'field_order',
                'reference' => 'reference',
                'db_primaryKey' => 'db_primaryKey',
                'db_nullable' => 'db_nullable',
                'db_unique' => 'db_unique',
                'calculable' => 'calculable',
                'calculable_sql' => 'calculable_sql',
                'description' => 'description',
            ];
        } else {
            return [
                'name' => __('PkgGapp::eDataField.name'),
                'e_model_reference' => __('PkgGapp::eDataField.e_model_reference'),
                'data_type' => __('PkgGapp::eDataField.data_type'),
                'default_value' => __('PkgGapp::eDataField.default_value'),
                'column_name' => __('PkgGapp::eDataField.column_name'),
                'e_relationship_reference' => __('PkgGapp::eDataField.e_relationship_reference'),
                'field_order' => __('PkgGapp::eDataField.field_order'),
                'reference' => __('Core::msg.reference'),
                'db_primaryKey' => __('PkgGapp::eDataField.db_primaryKey'),
                'db_nullable' => __('PkgGapp::eDataField.db_nullable'),
                'db_unique' => __('PkgGapp::eDataField.db_unique'),
                'calculable' => __('PkgGapp::eDataField.calculable'),
                'calculable_sql' => __('PkgGapp::eDataField.calculable_sql'),
                'description' => __('PkgGapp::eDataField.description'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($eDataField) {
            return [
                'name' => $eDataField->name,
                'e_model_reference' => $eDataField->eModel?->reference,
                'data_type' => $eDataField->data_type,
                'default_value' => $eDataField->default_value,
                'column_name' => $eDataField->column_name,
                'e_relationship_reference' => $eDataField->eRelationship?->reference,
                'field_order' => $eDataField->field_order,
                'reference' => $eDataField->reference,
                'db_primaryKey' => $eDataField->db_primaryKey,
                'db_nullable' => $eDataField->db_nullable,
                'db_unique' => $eDataField->db_unique,
                'calculable' => $eDataField->calculable,
                'calculable_sql' => $eDataField->calculable_sql,
                'description' => $eDataField->description,
            ];
        });
    }

    /**
     * Applique le style au fichier exporté
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Bordures pour toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Style spécifique pour les en-têtes
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Largeur automatique pour toutes les colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
