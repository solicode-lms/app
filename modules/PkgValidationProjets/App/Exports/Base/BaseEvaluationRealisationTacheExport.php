<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Exports\Base;

use Modules\PkgValidationProjets\Models\EvaluationRealisationTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEvaluationRealisationTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'reference' => 'reference',
            'note' => 'note',
            'message' => 'message',
            'evaluateur_id' => 'evaluateur_id',
            'realisation_tache_id' => 'realisation_tache_id',
        ];
        }else{
        return [
            'reference' => __('Core::msg.reference'),
            'note' => __('PkgValidationProjets::evaluationRealisationTache.note'),
            'message' => __('PkgValidationProjets::evaluationRealisationTache.message'),
            'evaluateur_id' => __('PkgValidationProjets::evaluationRealisationTache.evaluateur_id'),
            'realisation_tache_id' => __('PkgValidationProjets::evaluationRealisationTache.realisation_tache_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($evaluationRealisationTache) {
            return [
                'reference' => $evaluationRealisationTache->reference,
                'note' => $evaluationRealisationTache->note,
                'message' => $evaluationRealisationTache->message,
                'evaluateur_id' => $evaluationRealisationTache->evaluateur_id,
                'realisation_tache_id' => $evaluationRealisationTache->realisation_tache_id,
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
