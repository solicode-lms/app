<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Exports\Base;

use Modules\PkgQcm\Models\ReponseQcm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseReponseQcmExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'reference' => 'reference',
                'realisation_qcm_reference' => 'realisation_qcm_reference',
                'question_qcm_reference' => 'question_qcm_reference',
                'date_reponse' => 'date_reponse',
                'propositionReponses' => 'propositionReponses',
                'realisationUaProjets' => 'realisationUaProjets',
            ];
        } else {
            return [
                'reference' => __('Core::msg.reference'),
                'realisation_qcm_reference' => __('PkgQcm::realisationQcm.singular'),
                'question_qcm_reference' => __('PkgQcm::questionQcm.singular'),
                'date_reponse' => __('PkgQcm::reponseQcm.date_reponse'),
                    'propositionReponses' => __('PkgQcm::propositionReponse.plural'),
                    'realisationUaProjets' => __('PkgApprentissage::realisationUaProjet.plural'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($reponseQcm) {
            return [
                'reference' => $reponseQcm->reference,
                'realisation_qcm_reference' => $reponseQcm->realisationQcm?->reference,
                'question_qcm_reference' => $reponseQcm->questionQcm?->reference,
                'date_reponse' => $reponseQcm->date_reponse,
                'propositionReponses' => $reponseQcm->propositionReponses
                    ->pluck('reference')
                    ->implode('|'),
                'realisationUaProjets' => $reponseQcm->realisationUaProjets
                    ->pluck('reference')
                    ->implode('|'),
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
