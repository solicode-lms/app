<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGapp\App\Exports\Base;

use Modules\PkgGapp\Models\EMetadataDefinition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEMetadataDefinitionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'groupe' => 'groupe',
            'type' => 'type',
            'scope' => 'scope',
            'description' => 'description',
            'default_value' => 'default_value',
        ];
        }else{
        return [
            'reference' => __('Core::msg.reference'),
            'name' => __('PkgGapp::eMetadataDefinition.name'),
            'groupe' => __('PkgGapp::eMetadataDefinition.groupe'),
            'type' => __('PkgGapp::eMetadataDefinition.type'),
            'scope' => __('PkgGapp::eMetadataDefinition.scope'),
            'description' => __('PkgGapp::eMetadataDefinition.description'),
            'default_value' => __('PkgGapp::eMetadataDefinition.default_value'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($eMetadataDefinition) {
            return [
                'reference' => $eMetadataDefinition->reference,
                'name' => $eMetadataDefinition->name,
                'groupe' => $eMetadataDefinition->groupe,
                'type' => $eMetadataDefinition->type,
                'scope' => $eMetadataDefinition->scope,
                'description' => $eMetadataDefinition->description,
                'default_value' => $eMetadataDefinition->default_value,
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
