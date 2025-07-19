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
                'ordre' => 'ordre',
                'icon' => 'icon',
                'name' => 'name',
                'label' => 'label',
                'widget_type_reference' => 'widget_type_reference',
                'sys_model_reference' => 'sys_model_reference',
                'widget_operation_reference' => 'widget_operation_reference',
                'color' => 'color',
                'sys_color_reference' => 'sys_color_reference',
                'reference' => 'reference',
                'section_widget_reference' => 'section_widget_reference',
                'parameters' => 'parameters',
            ];
        } else {
            return [
                'ordre' => __('PkgWidgets::widget.ordre'),
                'icon' => __('PkgWidgets::widget.icon'),
                'name' => __('PkgWidgets::widget.name'),
                'label' => __('PkgWidgets::widget.label'),
                'widget_type_reference' => __('PkgWidgets::widget.widget_type_reference'),
                'sys_model_reference' => __('PkgWidgets::widget.sys_model_reference'),
                'widget_operation_reference' => __('PkgWidgets::widget.widget_operation_reference'),
                'color' => __('PkgWidgets::widget.color'),
                'sys_color_reference' => __('PkgWidgets::widget.sys_color_reference'),
                'reference' => __('Core::msg.reference'),
                'section_widget_reference' => __('PkgWidgets::widget.section_widget_reference'),
                'parameters' => __('PkgWidgets::widget.parameters'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($widget) {
            return [
                'ordre' => $widget->ordre,
                'icon' => $widget->icon,
                'name' => $widget->name,
                'label' => $widget->label,
                'widget_type_reference' => $widget->type?->reference,
                'sys_model_reference' => $widget->model?->reference,
                'widget_operation_reference' => $widget->operation?->reference,
                'color' => $widget->color,
                'sys_color_reference' => $widget->sysColor?->reference,
                'reference' => $widget->reference,
                'section_widget_reference' => $widget->sectionWidget?->reference,
                'parameters' => $widget->parameters,
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
