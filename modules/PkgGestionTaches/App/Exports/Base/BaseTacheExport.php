<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\Tache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'titre' => 'titre',
            'priorite_tache_id' => 'priorite_tache_id',
            'projet_id' => 'projet_id',
            'description' => 'description',
            'dateDebut' => 'dateDebut',
            'dateFin' => 'dateFin',
            'reference' => 'reference',
            'note' => 'note',
        ];
        }else{
        return [
            'ordre' => __('PkgRealisationTache::tache.ordre'),
            'titre' => __('PkgRealisationTache::tache.titre'),
            'priorite_tache_id' => __('PkgRealisationTache::tache.priorite_tache_id'),
            'projet_id' => __('PkgRealisationTache::tache.projet_id'),
            'description' => __('PkgRealisationTache::tache.description'),
            'dateDebut' => __('PkgRealisationTache::tache.dateDebut'),
            'dateFin' => __('PkgRealisationTache::tache.dateFin'),
            'reference' => __('Core::msg.reference'),
            'note' => __('PkgRealisationTache::tache.note'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($tache) {
            return [
                'ordre' => $tache->ordre,
                'titre' => $tache->titre,
                'priorite_tache_id' => $tache->priorite_tache_id,
                'projet_id' => $tache->projet_id,
                'description' => $tache->description,
                'dateDebut' => $tache->dateDebut,
                'dateFin' => $tache->dateFin,
                'reference' => $tache->reference,
                'note' => $tache->note,
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
