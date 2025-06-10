<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Exports\Base;

use Modules\PkgValidationProjets\Models\EvaluationRealisationProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEvaluationRealisationProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'realisation_projet_id' => 'realisation_projet_id',
            'evaluateur_id' => 'evaluateur_id',
            'date_evaluation' => 'date_evaluation',
            'etat_evaluation_projet_id' => 'etat_evaluation_projet_id',
            'remarques' => 'remarques',
            'reference' => 'reference',
        ];
        }else{
        return [
            'realisation_projet_id' => __('PkgValidationProjets::evaluationRealisationProjet.realisation_projet_id'),
            'evaluateur_id' => __('PkgValidationProjets::evaluationRealisationProjet.evaluateur_id'),
            'date_evaluation' => __('PkgValidationProjets::evaluationRealisationProjet.date_evaluation'),
            'etat_evaluation_projet_id' => __('PkgValidationProjets::evaluationRealisationProjet.etat_evaluation_projet_id'),
            'remarques' => __('PkgValidationProjets::evaluationRealisationProjet.remarques'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($evaluationRealisationProjet) {
            return [
                'realisation_projet_id' => $evaluationRealisationProjet->realisation_projet_id,
                'evaluateur_id' => $evaluationRealisationProjet->evaluateur_id,
                'date_evaluation' => $evaluationRealisationProjet->date_evaluation,
                'etat_evaluation_projet_id' => $evaluationRealisationProjet->etat_evaluation_projet_id,
                'remarques' => $evaluationRealisationProjet->remarques,
                'reference' => $evaluationRealisationProjet->reference,
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
