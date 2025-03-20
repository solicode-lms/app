<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Exports\Base;

use Modules\PkgAutoformation\Models\RealisationChapitre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationChapitreExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'date_debut' => 'date_debut',
            'date_fin' => 'date_fin',
            'reference' => 'reference',
            'chapitre_id' => 'chapitre_id',
            'realisation_formation_id' => 'realisation_formation_id',
            'etat_chapitre_id' => 'etat_chapitre_id',
        ];
        }else{
        return [
            'date_debut' => __('PkgAutoformation::realisationChapitre.date_debut'),
            'date_fin' => __('PkgAutoformation::realisationChapitre.date_fin'),
            'reference' => __('Core::msg.reference'),
            'chapitre_id' => __('PkgAutoformation::realisationChapitre.chapitre_id'),
            'realisation_formation_id' => __('PkgAutoformation::realisationChapitre.realisation_formation_id'),
            'etat_chapitre_id' => __('PkgAutoformation::realisationChapitre.etat_chapitre_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($realisationChapitre) {
            return [
                'date_debut' => $realisationChapitre->date_debut,
                'date_fin' => $realisationChapitre->date_fin,
                'reference' => $realisationChapitre->reference,
                'chapitre_id' => $realisationChapitre->chapitre_id,
                'realisation_formation_id' => $realisationChapitre->realisation_formation_id,
                'etat_chapitre_id' => $realisationChapitre->etat_chapitre_id,
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
