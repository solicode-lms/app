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
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEMetadatumExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'reference' => 'reference',
                'value_boolean' => 'value_boolean',
                'value_string' => 'value_string',
                'value_integer' => 'value_integer',
                'value_float' => 'value_float',
                'value_date' => 'value_date',
                'value_datetime' => 'value_datetime',
                'value_enum' => 'value_enum',
                'value_json' => 'value_json',
                'value_text' => 'value_text',
                'e_model_reference' => 'e_model_reference',
                'e_data_field_reference' => 'e_data_field_reference',
                'e_metadata_definition_reference' => 'e_metadata_definition_reference',
            ];
        } else {
            return [
                'reference' => __('Core::msg.reference'),
                'value_boolean' => __('PkgGapp::eMetadatum.value_boolean'),
                'value_string' => __('PkgGapp::eMetadatum.value_string'),
                'value_integer' => __('PkgGapp::eMetadatum.value_integer'),
                'value_float' => __('PkgGapp::eMetadatum.value_float'),
                'value_date' => __('PkgGapp::eMetadatum.value_date'),
                'value_datetime' => __('PkgGapp::eMetadatum.value_datetime'),
                'value_enum' => __('PkgGapp::eMetadatum.value_enum'),
                'value_json' => __('PkgGapp::eMetadatum.value_json'),
                'value_text' => __('PkgGapp::eMetadatum.value_text'),
                'e_model_reference' => __('PkgGapp::eMetadatum.e_model_reference'),
                'e_data_field_reference' => __('PkgGapp::eMetadatum.e_data_field_reference'),
                'e_metadata_definition_reference' => __('PkgGapp::eMetadatum.e_metadata_definition_reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($eMetadatum) {
            return [
                'reference' => $eMetadatum->reference,
                'value_boolean' => $eMetadatum->value_boolean ? '1' : '0',
                'value_string' => $eMetadatum->value_string,
                'value_integer' => (string) $eMetadatum->value_integer,
                'value_float' => $eMetadatum->value_float,
                'value_date' => $eMetadatum->value_date,
                'value_datetime' => $eMetadatum->value_datetime,
                'value_enum' => $eMetadatum->value_enum,
                'value_json' => $eMetadatum->value_json,
                'value_text' => $eMetadatum->value_text,
                'e_model_reference' => $eMetadatum->eModel?->reference,
                'e_data_field_reference' => $eMetadatum->eDataField?->reference,
                'e_metadata_definition_reference' => $eMetadatum->eMetadataDefinition?->reference,
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
