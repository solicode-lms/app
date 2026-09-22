<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Exports\Base;

use Modules\PkgQcm\Models\PropositionReponse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BasePropositionReponseExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'reference' => 'reference',
                'libelle' => 'libelle',
                'is_correcte' => 'is_correcte',
                'question_lib_reference' => 'question_lib_reference',
                'reponseQcms' => 'reponseQcms',
            ];
        } else {
            return [
                'ordre' => __('PkgQcm::propositionReponse.ordre'),
                'reference' => __('Core::msg.reference'),
                'libelle' => __('PkgQcm::propositionReponse.libelle'),
                'is_correcte' => __('PkgQcm::propositionReponse.is_correcte'),
                'question_lib_reference' => __('PkgQcm::questionLib.singular'),
                    'reponseQcms' => __('PkgQcm::reponseQcm.plural'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($propositionReponse) {
            return [
                'ordre' => (string) $propositionReponse->ordre,
                'reference' => $propositionReponse->reference,
                'libelle' => $propositionReponse->libelle,
                'is_correcte' => $propositionReponse->is_correcte ? '1' : '0',
                'question_lib_reference' => $propositionReponse->questionLib?->reference,
                'reponseQcms' => $propositionReponse->reponseQcms
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
