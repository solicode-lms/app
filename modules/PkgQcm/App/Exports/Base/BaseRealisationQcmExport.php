<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgQcm\App\Exports\Base;

use Modules\PkgQcm\Models\RealisationQcm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationQcmExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'affectation_qcm_projet_reference' => 'affectation_qcm_projet_reference',
                'qcm_reference' => 'qcm_reference',
                'apprenant_reference' => 'user_reference',
                'etat_realisation_qcm_reference' => 'etat_realisation_qcm_reference',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'date_soumission' => 'date_soumission',
                'date_validation' => 'date_validation',
                'note_obtenu' => 'note_obtenu',
                'statut' => 'statut',
            ];
        } else {
            return [
                'reference' => __('Core::msg.reference'),
                'affectation_qcm_projet_reference' => __('PkgQcm::affectationQcmProjet.singular'),
                'qcm_reference' => __('PkgQcm::qcm.singular'),
                'apprenant_reference' => __('PkgAutorisation::user.singular'),
                'etat_realisation_qcm_reference' => __('PkgQcm::etatRealisationQcm.singular'),
                'date_debut' => __('PkgQcm::realisationQcm.date_debut'),
                'date_fin' => __('PkgQcm::realisationQcm.date_fin'),
                'date_soumission' => __('PkgQcm::realisationQcm.date_soumission'),
                'date_validation' => __('PkgQcm::realisationQcm.date_validation'),
                'note_obtenu' => __('PkgQcm::realisationQcm.note_obtenu'),
                'statut' => __('PkgQcm::realisationQcm.statut'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationQcm) {
            return [
                'reference' => $realisationQcm->reference,
                'affectation_qcm_projet_reference' => $realisationQcm->affectationQcmProjet?->reference,
                'qcm_reference' => $realisationQcm->qcm?->reference,
                'user_reference' => $realisationQcm->apprenant?->reference,
                'etat_realisation_qcm_reference' => $realisationQcm->etatRealisationQcm?->reference,
                'date_debut' => $realisationQcm->date_debut,
                'date_fin' => $realisationQcm->date_fin,
                'date_soumission' => $realisationQcm->date_soumission,
                'date_validation' => $realisationQcm->date_validation,
                'note_obtenu' => $realisationQcm->note_obtenu,
                'statut' => $realisationQcm->statut,
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
