<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\App\Exports\Base;

use Modules\PkgEvaluateurs\Models\EvaluationRealisationProjet;
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
    protected $format;

    public function __construct($data, $format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    /**
     * Génère les en-têtes du fichier exporté
     */
    public function headings(): array
    {
        if ($this->format === 'csv') {
            return [
                'realisation_projet_reference' => 'realisation_projet_reference',
                'evaluateur_reference' => 'evaluateur_reference',
                'date_evaluation' => 'date_evaluation',
                'etat_evaluation_projet_reference' => 'etat_evaluation_projet_reference',
                'remarques' => 'remarques',
                'reference' => 'reference',
            ];
        } else {
            return [
                'realisation_projet_reference' => __('PkgRealisationProjets::realisationProjet.singular'),
                'evaluateur_reference' => __('PkgEvaluateurs::evaluateur.singular'),
                'date_evaluation' => __('PkgEvaluateurs::evaluationRealisationProjet.date_evaluation'),
                'etat_evaluation_projet_reference' => __('PkgEvaluateurs::etatEvaluationProjet.singular'),
                'remarques' => __('PkgEvaluateurs::evaluationRealisationProjet.remarques'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($evaluationRealisationProjet) {
            return [
                'realisation_projet_reference' => $evaluationRealisationProjet->realisationProjet?->reference,
                'evaluateur_reference' => $evaluationRealisationProjet->evaluateur?->reference,
                'date_evaluation' => $evaluationRealisationProjet->date_evaluation,
                'etat_evaluation_projet_reference' => $evaluationRealisationProjet->etatEvaluationProjet?->reference,
                'remarques' => $evaluationRealisationProjet->remarques,
                'reference' => $evaluationRealisationProjet->reference,
            ];
        });
    }

    /**
     * Applique le style au fichier exporté
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Bordures pour toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Style spécifique pour les en-têtes
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Largeur automatique pour toutes les colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
