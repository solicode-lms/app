<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Exports\Base;

use Modules\PkgAutoformation\Models\RealisationFormation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationFormationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'formation_id' => 'formation_id',
            'apprenant_id' => 'apprenant_id',
            'etat_formation_id' => 'etat_formation_id',
        ];
        }else{
        return [
            'date_debut' => __('PkgAutoformation::realisationFormation.date_debut'),
            'date_fin' => __('PkgAutoformation::realisationFormation.date_fin'),
            'reference' => __('Core::msg.reference'),
            'formation_id' => __('PkgAutoformation::realisationFormation.formation_id'),
            'apprenant_id' => __('PkgAutoformation::realisationFormation.apprenant_id'),
            'etat_formation_id' => __('PkgAutoformation::realisationFormation.etat_formation_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($realisationFormation) {
            return [
                'date_debut' => $realisationFormation->date_debut,
                'date_fin' => $realisationFormation->date_fin,
                'reference' => $realisationFormation->reference,
                'formation_id' => $realisationFormation->formation_id,
                'apprenant_id' => $realisationFormation->apprenant_id,
                'etat_formation_id' => $realisationFormation->etat_formation_id,
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
