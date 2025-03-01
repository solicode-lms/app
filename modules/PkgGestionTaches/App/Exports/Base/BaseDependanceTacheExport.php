<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Exports\Base;

use Modules\PkgGestionTaches\Models\DependanceTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseDependanceTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'tache_id' => 'tache_id',
            'tache_cible_id' => 'tache_cible_id',
            'type_dependance_tache_id' => 'type_dependance_tache_id',
        ];
        }else{
        return [
            'reference' => __('Core::msg.reference'),
            'tache_id' => __('PkgGestionTaches::dependanceTache.tache_id'),
            'tache_cible_id' => __('PkgGestionTaches::dependanceTache.tache_cible_id'),
            'type_dependance_tache_id' => __('PkgGestionTaches::dependanceTache.type_dependance_tache_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($dependanceTache) {
            return [
                'reference' => $dependanceTache->reference,
                'tache_id' => $dependanceTache->tache_id,
                'tache_cible_id' => $dependanceTache->tache_cible_id,
                'type_dependance_tache_id' => $dependanceTache->type_dependance_tache_id,
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
