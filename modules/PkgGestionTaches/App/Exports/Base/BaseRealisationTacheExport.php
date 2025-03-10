<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Exports\Base;

use Modules\PkgGestionTaches\Models\RealisationTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'tache_id' => 'tache_id',
            'realisation_projet_id' => 'realisation_projet_id',
            'dateDebut' => 'dateDebut',
            'dateFin' => 'dateFin',
            'reference' => 'reference',
            'etat_realisation_tache_id' => 'etat_realisation_tache_id',
            'remarques_formateur' => 'remarques_formateur',
            'remarques_apprenant' => 'remarques_apprenant',
        ];
        }else{
        return [
            'tache_id' => __('PkgGestionTaches::realisationTache.tache_id'),
            'realisation_projet_id' => __('PkgGestionTaches::realisationTache.realisation_projet_id'),
            'dateDebut' => __('PkgGestionTaches::realisationTache.dateDebut'),
            'dateFin' => __('PkgGestionTaches::realisationTache.dateFin'),
            'reference' => __('Core::msg.reference'),
            'etat_realisation_tache_id' => __('PkgGestionTaches::realisationTache.etat_realisation_tache_id'),
            'remarques_formateur' => __('PkgGestionTaches::realisationTache.remarques_formateur'),
            'remarques_apprenant' => __('PkgGestionTaches::realisationTache.remarques_apprenant'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($realisationTache) {
            return [
                'tache_id' => $realisationTache->tache_id,
                'realisation_projet_id' => $realisationTache->realisation_projet_id,
                'dateDebut' => $realisationTache->dateDebut,
                'dateFin' => $realisationTache->dateFin,
                'reference' => $realisationTache->reference,
                'etat_realisation_tache_id' => $realisationTache->etat_realisation_tache_id,
                'remarques_formateur' => $realisationTache->remarques_formateur,
                'remarques_apprenant' => $realisationTache->remarques_apprenant,
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
