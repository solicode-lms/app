<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseHistoriqueRealisationTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'changement' => 'changement',
            'dateModification' => 'dateModification',
            'realisation_tache_id' => 'realisation_tache_id',
            'user_id' => 'user_id',
            'reference' => 'reference',
            'isFeedback' => 'isFeedback',
        ];
        }else{
        return [
            'changement' => __('PkgRealisationTache::historiqueRealisationTache.changement'),
            'dateModification' => __('PkgRealisationTache::historiqueRealisationTache.dateModification'),
            'realisation_tache_id' => __('PkgRealisationTache::historiqueRealisationTache.realisation_tache_id'),
            'user_id' => __('PkgRealisationTache::historiqueRealisationTache.user_id'),
            'reference' => __('Core::msg.reference'),
            'isFeedback' => __('PkgRealisationTache::historiqueRealisationTache.isFeedback'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($historiqueRealisationTache) {
            return [
                'changement' => $historiqueRealisationTache->changement,
                'dateModification' => $historiqueRealisationTache->dateModification,
                'realisation_tache_id' => $historiqueRealisationTache->realisation_tache_id,
                'user_id' => $historiqueRealisationTache->user_id,
                'reference' => $historiqueRealisationTache->reference,
                'isFeedback' => $historiqueRealisationTache->isFeedback,
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
