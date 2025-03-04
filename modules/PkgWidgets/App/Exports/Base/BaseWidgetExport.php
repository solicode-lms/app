<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Exports\Base;

use Modules\PkgWidgets\Models\Widget;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseWidgetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'name' => 'name',
            'label' => 'label',
            'model_id' => 'model_id',
            'type_id' => 'type_id',
            'operation_id' => 'operation_id',
            'color' => 'color',
            'icon' => 'icon',
            'parameters' => 'parameters',
            'reference' => 'reference',
        ];
        }else{
        return [
            'name' => __('PkgWidgets::widget.name'),
            'label' => __('PkgWidgets::widget.label'),
            'model_id' => __('PkgWidgets::widget.model_id'),
            'type_id' => __('PkgWidgets::widget.type_id'),
            'operation_id' => __('PkgWidgets::widget.operation_id'),
            'color' => __('PkgWidgets::widget.color'),
            'icon' => __('PkgWidgets::widget.icon'),
            'parameters' => __('PkgWidgets::widget.parameters'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($widget) {
            return [
                'name' => $widget->name,
                'label' => $widget->label,
                'model_id' => $widget->model_id,
                'type_id' => $widget->type_id,
                'operation_id' => $widget->operation_id,
                'color' => $widget->color,
                'icon' => $widget->icon,
                'parameters' => $widget->parameters,
                'reference' => $widget->reference,
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
