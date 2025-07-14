<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Exports\Base;

use Modules\PkgCompetences\Models\Chapitre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseChapitreExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'reference' => 'reference',
            'nom' => 'nom',
            'lien' => 'lien',
            'description' => 'description',
            'isOfficiel' => 'isOfficiel',
            'unite_apprentissage_id' => 'unite_apprentissage_id',
            'formateur_id' => 'formateur_id',
        ];
        }else{
        return [
            'ordre' => __('PkgCompetences::chapitre.ordre'),
            'reference' => __('Core::msg.reference'),
            'nom' => __('PkgCompetences::chapitre.nom'),
            'lien' => __('PkgCompetences::chapitre.lien'),
            'description' => __('PkgCompetences::chapitre.description'),
            'isOfficiel' => __('PkgCompetences::chapitre.isOfficiel'),
            'unite_apprentissage_id' => __('PkgCompetences::chapitre.unite_apprentissage_id'),
            'formateur_id' => __('PkgCompetences::chapitre.formateur_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($chapitre) {
            return [
                'ordre' => $chapitre->ordre,
                'reference' => $chapitre->reference,
                'nom' => $chapitre->nom,
                'lien' => $chapitre->lien,
                'description' => $chapitre->description,
                'isOfficiel' => $chapitre->isOfficiel,
                'unite_apprentissage_id' => $chapitre->unite_apprentissage_id,
                'formateur_id' => $chapitre->formateur_id,
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
