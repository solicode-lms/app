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
                'realisation_tache_reference' => 'realisation_tache_reference',
                'evaluateur_reference' => 'evaluateur_reference',
                'note' => 'note',
                'message' => 'message',
                'evaluation_realisation_projet_reference' => 'evaluation_realisation_projet_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'realisation_tache_reference' => __('PkgRealisationTache::realisationTache.singular'),
                'evaluateur_reference' => __('PkgEvaluateurs::evaluateur.singular'),
                'note' => __('PkgEvaluateurs::evaluationRealisationTache.note'),
                'message' => __('PkgEvaluateurs::evaluationRealisationTache.message'),
                'evaluation_realisation_projet_reference' => __('PkgEvaluateurs::evaluationRealisationProjet.singular'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($evaluationRealisationTache) {
            return [
                'realisation_tache_reference' => $evaluationRealisationTache->realisationTache?->reference,
                'evaluateur_reference' => $evaluationRealisationTache->evaluateur?->reference,
                'note' => $evaluationRealisationTache->note,
                'message' => $evaluationRealisationTache->message,
                'evaluation_realisation_projet_reference' => $evaluationRealisationTache->evaluationRealisationProjet?->reference,
                'reference' => $evaluationRealisationTache->reference,
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
