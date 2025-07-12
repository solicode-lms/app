<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\App\Exports\Base;

use Modules\PkgEvaluateurs\Models\EvaluationRealisationTache;
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
            'realisation_tache_id' => 'realisation_tache_id',
            'evaluateur_id' => 'evaluateur_id',
            'note' => 'note',
            'message' => 'message',
            'evaluation_realisation_projet_id' => 'evaluation_realisation_projet_id',
            'reference' => 'reference',
        ];
        }else{
        return [
            'realisation_tache_id' => __('PkgEvaluateurs::evaluationRealisationTache.realisation_tache_id'),
            'evaluateur_id' => __('PkgEvaluateurs::evaluationRealisationTache.evaluateur_id'),
            'note' => __('PkgEvaluateurs::evaluationRealisationTache.note'),
            'message' => __('PkgEvaluateurs::evaluationRealisationTache.message'),
            'evaluation_realisation_projet_id' => __('PkgEvaluateurs::evaluationRealisationTache.evaluation_realisation_projet_id'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($evaluationRealisationTache) {
            return [
                'realisation_tache_id' => $evaluationRealisationTache->realisation_tache_id,
                'evaluateur_id' => $evaluationRealisationTache->evaluateur_id,
                'note' => $evaluationRealisationTache->note,
                'message' => $evaluationRealisationTache->message,
                'evaluation_realisation_projet_id' => $evaluationRealisationTache->evaluation_realisation_projet_id,
                'reference' => $evaluationRealisationTache->reference,
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
