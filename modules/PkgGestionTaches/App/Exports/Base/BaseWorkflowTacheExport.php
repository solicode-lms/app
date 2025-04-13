<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Exports\Base;

use Modules\PkgGestionTaches\Models\WorkflowTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseWorkflowTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'code' => 'code',
            'titre' => 'titre',
            'description' => 'description',
            'reference' => 'reference',
            'sys_color_id' => 'sys_color_id',
        ];
        }else{
        return [
            'code' => __('PkgGestionTaches::workflowTache.code'),
            'titre' => __('PkgGestionTaches::workflowTache.titre'),
            'description' => __('PkgGestionTaches::workflowTache.description'),
            'reference' => __('Core::msg.reference'),
            'sys_color_id' => __('PkgGestionTaches::workflowTache.sys_color_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($workflowTache) {
            return [
                'code' => $workflowTache->code,
                'titre' => $workflowTache->titre,
                'description' => $workflowTache->description,
                'reference' => $workflowTache->reference,
                'sys_color_id' => $workflowTache->sys_color_id,
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
