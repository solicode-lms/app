<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgWidgets\App\Exports\Base;

use Modules\PkgWidgets\Models\WidgetUtilisateur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseWidgetUtilisateurExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'user_id' => 'user_id',
            'widget_id' => 'widget_id',
            'titre' => 'titre',
            'sous_titre' => 'sous_titre',
            'visible' => 'visible',
        ];
        }else{
        return [
            'ordre' => __('PkgWidgets::widgetUtilisateur.ordre'),
            'user_id' => __('PkgWidgets::widgetUtilisateur.user_id'),
            'widget_id' => __('PkgWidgets::widgetUtilisateur.widget_id'),
            'titre' => __('PkgWidgets::widgetUtilisateur.titre'),
            'sous_titre' => __('PkgWidgets::widgetUtilisateur.sous_titre'),
            'visible' => __('PkgWidgets::widgetUtilisateur.visible'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($widgetUtilisateur) {
            return [
                'ordre' => $widgetUtilisateur->ordre,
                'user_id' => $widgetUtilisateur->user_id,
                'widget_id' => $widgetUtilisateur->widget_id,
                'titre' => $widgetUtilisateur->titre,
                'sous_titre' => $widgetUtilisateur->sous_titre,
                'visible' => $widgetUtilisateur->visible,
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
