<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Exports\Base;

use Modules\PkgCompetences\Models\UniteApprentissage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseUniteApprentissageExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'code' => 'code',
            'reference' => 'reference',
            'nom' => 'nom',
            'lien' => 'lien',
            'description' => 'description',
            'micro_competence_id' => 'micro_competence_id',
        ];
        }else{
        return [
            'ordre' => __('PkgCompetences::uniteApprentissage.ordre'),
            'code' => __('PkgCompetences::uniteApprentissage.code'),
            'reference' => __('Core::msg.reference'),
            'nom' => __('PkgCompetences::uniteApprentissage.nom'),
            'lien' => __('PkgCompetences::uniteApprentissage.lien'),
            'description' => __('PkgCompetences::uniteApprentissage.description'),
            'micro_competence_id' => __('PkgCompetences::uniteApprentissage.micro_competence_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($uniteApprentissage) {
            return [
                'ordre' => $uniteApprentissage->ordre,
                'code' => $uniteApprentissage->code,
                'reference' => $uniteApprentissage->reference,
                'nom' => $uniteApprentissage->nom,
                'lien' => $uniteApprentissage->lien,
                'description' => $uniteApprentissage->description,
                'micro_competence_id' => $uniteApprentissage->micro_competence_id,
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
