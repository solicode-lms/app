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
                'title' => 'title',
                'type' => 'type',
                'message' => 'message',
                'sent_at' => 'sent_at',
                'is_read' => 'is_read',
                'user_reference' => 'user_reference',
                'data' => 'data',
            ];
        } else {
            return [
                'title' => __('PkgNotification::notification.title'),
                'type' => __('PkgNotification::notification.type'),
                'message' => __('PkgNotification::notification.message'),
                'sent_at' => __('PkgNotification::notification.sent_at'),
                'is_read' => __('PkgNotification::notification.is_read'),
                'user_reference' => __('PkgNotification::notification.user_reference'),
                'data' => __('PkgNotification::notification.data'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($notification) {
            return [
                'title' => $notification->title,
                'type' => $notification->type,
                'message' => $notification->message,
                'sent_at' => $notification->sent_at,
                'is_read' => $notification->is_read ? '1' : '0',
                'user_reference' => $notification->user?->reference,
                'data' => $notification->data,
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
