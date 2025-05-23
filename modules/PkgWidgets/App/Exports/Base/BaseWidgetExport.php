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
            'ordre' => 'ordre',
            'icon' => 'icon',
            'name' => 'name',
            'label' => 'label',
            'type_id' => 'type_id',
            'model_id' => 'model_id',
            'operation_id' => 'operation_id',
            'color' => 'color',
            'sys_color_id' => 'sys_color_id',
            'reference' => 'reference',
            'section_widget_id' => 'section_widget_id',
            'parameters' => 'parameters',
        ];
        }else{
        return [
            'ordre' => __('PkgWidgets::widget.ordre'),
            'icon' => __('PkgWidgets::widget.icon'),
            'name' => __('PkgWidgets::widget.name'),
            'label' => __('PkgWidgets::widget.label'),
            'type_id' => __('PkgWidgets::widget.type_id'),
            'model_id' => __('PkgWidgets::widget.model_id'),
            'operation_id' => __('PkgWidgets::widget.operation_id'),
            'color' => __('PkgWidgets::widget.color'),
            'sys_color_id' => __('PkgWidgets::widget.sys_color_id'),
            'reference' => __('Core::msg.reference'),
            'section_widget_id' => __('PkgWidgets::widget.section_widget_id'),
            'parameters' => __('PkgWidgets::widget.parameters'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($widget) {
            return [
                'ordre' => $widget->ordre,
                'icon' => $widget->icon,
                'name' => $widget->name,
                'label' => $widget->label,
                'type_id' => $widget->type_id,
                'model_id' => $widget->model_id,
                'operation_id' => $widget->operation_id,
                'color' => $widget->color,
                'sys_color_id' => $widget->sys_color_id,
                'reference' => $widget->reference,
                'section_widget_id' => $widget->section_widget_id,
                'parameters' => $widget->parameters,
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
