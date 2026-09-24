<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Exports\Base;

use Modules\PkgQcm\Models\Question;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseQuestionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'ordre' => 'ordre',
                'enonce' => 'enonce',
                'explication' => 'explication',
                'type' => 'type',
                'is_actif' => 'is_actif',
                'bareme' => 'bareme',
                'qcm_reference' => 'qcm_reference',
                'unite_apprentissage_reference' => 'unite_apprentissage_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'ordre' => __('PkgQcm::question.ordre'),
                'enonce' => __('PkgQcm::question.enonce'),
                'explication' => __('PkgQcm::question.explication'),
                'type' => __('PkgQcm::question.type'),
                'is_actif' => __('PkgQcm::question.is_actif'),
                'bareme' => __('PkgQcm::question.bareme'),
                'qcm_reference' => __('PkgQcm::qcm.singular'),
                'unite_apprentissage_reference' => __('PkgCompetences::uniteApprentissage.singular'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($question) {
            return [
                'ordre' => (string) $question->ordre,
                'enonce' => $question->enonce,
                'explication' => $question->explication,
                'type' => $question->type,
                'is_actif' => $question->is_actif ? '1' : '0',
                'bareme' => $question->bareme,
                'qcm_reference' => $question->qcm?->reference,
                'unite_apprentissage_reference' => $question->uniteApprentissage?->reference,
                'reference' => $question->reference,
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
