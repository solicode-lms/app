<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Exports\Base;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'affectation_projet_id' => 'affectation_projet_id',
            'apprenant_id' => 'apprenant_id',
            'etats_realisation_projet_id' => 'etats_realisation_projet_id',
            'date_debut' => 'date_debut',
            'date_fin' => 'date_fin',
            'reference' => 'reference',
            'rapport' => 'rapport',
        ];
        }else{
        return [
            'affectation_projet_id' => __('PkgRealisationProjets::realisationProjet.affectation_projet_id'),
            'apprenant_id' => __('PkgRealisationProjets::realisationProjet.apprenant_id'),
            'etats_realisation_projet_id' => __('PkgRealisationProjets::realisationProjet.etats_realisation_projet_id'),
            'date_debut' => __('PkgRealisationProjets::realisationProjet.date_debut'),
            'date_fin' => __('PkgRealisationProjets::realisationProjet.date_fin'),
            'reference' => __('Core::msg.reference'),
            'rapport' => __('PkgRealisationProjets::realisationProjet.rapport'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($realisationProjet) {
            return [
                'affectation_projet_id' => $realisationProjet->affectation_projet_id,
                'apprenant_id' => $realisationProjet->apprenant_id,
                'etats_realisation_projet_id' => $realisationProjet->etats_realisation_projet_id,
                'date_debut' => $realisationProjet->date_debut,
                'date_fin' => $realisationProjet->date_fin,
                'reference' => $realisationProjet->reference,
                'rapport' => $realisationProjet->rapport,
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
