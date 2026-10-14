<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\RevisionTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRevisionTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'realisation_tache_reference' => 'realisation_tache_reference',
                'tentative' => 'tentative',
                'date_demande' => 'date_demande',
                'message_formateur' => 'message_formateur',
                'date_soumission' => 'date_soumission',
                'date_validation' => 'date_validation',
                'est_valide' => 'est_valide',
            ];
        } else {
            return [
                'reference' => __('Core::msg.reference'),
                'realisation_tache_reference' => __('PkgRealisationTache::realisationTache.singular'),
                'tentative' => __('PkgRealisationTache::revisionTache.tentative'),
                'date_demande' => __('PkgRealisationTache::revisionTache.date_demande'),
                'message_formateur' => __('PkgRealisationTache::revisionTache.message_formateur'),
                'date_soumission' => __('PkgRealisationTache::revisionTache.date_soumission'),
                'date_validation' => __('PkgRealisationTache::revisionTache.date_validation'),
                'est_valide' => __('PkgRealisationTache::revisionTache.est_valide'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($revisionTache) {
            return [
                'reference' => $revisionTache->reference,
                'realisation_tache_reference' => $revisionTache->realisationTache?->reference,
                'tentative' => (string) $revisionTache->tentative,
                'date_demande' => $revisionTache->date_demande,
                'message_formateur' => $revisionTache->message_formateur,
                'date_soumission' => $revisionTache->date_soumission,
                'date_validation' => $revisionTache->date_validation,
                'est_valide' => $revisionTache->est_valide ? '1' : '0',
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
