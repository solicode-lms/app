<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgNotification\App\Exports\Base;

use Modules\PkgNotification\Models\Notification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseNotificationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'title' => 'title',
            'type' => 'type',
            'message' => 'message',
            'sent_at' => 'sent_at',
            'is_read' => 'is_read',
            'user_id' => 'user_id',
            'data' => 'data',
        ];
        }else{
        return [
            'title' => __('PkgNotification::notification.title'),
            'type' => __('PkgNotification::notification.type'),
            'message' => __('PkgNotification::notification.message'),
            'sent_at' => __('PkgNotification::notification.sent_at'),
            'is_read' => __('PkgNotification::notification.is_read'),
            'user_id' => __('PkgNotification::notification.user_id'),
            'data' => __('PkgNotification::notification.data'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($notification) {
            return [
                'title' => $notification->title,
                'type' => $notification->type,
                'message' => $notification->message,
                'sent_at' => $notification->sent_at,
                'is_read' => $notification->is_read,
                'user_id' => $notification->user_id,
                'data' => $notification->data,
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
