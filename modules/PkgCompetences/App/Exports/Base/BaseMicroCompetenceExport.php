<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Exports\Base;

use Modules\PkgCompetences\Models\MicroCompetence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseMicroCompetenceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'titre' => 'titre',
            'sous_titre' => 'sous_titre',
            'competence_id' => 'competence_id',
            'lien' => 'lien',
            'description' => 'description',
            'reference' => 'reference',
        ];
        }else{
        return [
            'ordre' => __('PkgCompetences::microCompetence.ordre'),
            'code' => __('PkgCompetences::microCompetence.code'),
            'titre' => __('PkgCompetences::microCompetence.titre'),
            'sous_titre' => __('PkgCompetences::microCompetence.sous_titre'),
            'competence_id' => __('PkgCompetences::microCompetence.competence_id'),
            'lien' => __('PkgCompetences::microCompetence.lien'),
            'description' => __('PkgCompetences::microCompetence.description'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($microCompetence) {
            return [
                'ordre' => $microCompetence->ordre,
                'code' => $microCompetence->code,
                'titre' => $microCompetence->titre,
                'sous_titre' => $microCompetence->sous_titre,
                'competence_id' => $microCompetence->competence_id,
                'lien' => $microCompetence->lien,
                'description' => $microCompetence->description,
                'reference' => $microCompetence->reference,
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
