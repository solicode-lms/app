<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\HistoriqueRealisationTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseHistoriqueRealisationTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'changement' => 'changement',
                'dateModification' => 'dateModification',
                'realisation_tache_reference' => 'realisation_tache_reference',
                'user_reference' => 'user_reference',
                'reference' => 'reference',
                'isFeedback' => 'isFeedback',
            ];
        } else {
            return [
                'changement' => __('PkgRealisationTache::historiqueRealisationTache.changement'),
                'dateModification' => __('PkgRealisationTache::historiqueRealisationTache.dateModification'),
                'realisation_tache_reference' => __('PkgRealisationTache::realisationTache.singular'),
                'user_reference' => __('PkgAutorisation::user.singular'),
                'reference' => __('Core::msg.reference'),
                'isFeedback' => __('PkgRealisationTache::historiqueRealisationTache.isFeedback'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($historiqueRealisationTache) {
            return [
                'changement' => $historiqueRealisationTache->changement,
                'dateModification' => $historiqueRealisationTache->dateModification,
                'realisation_tache_reference' => $historiqueRealisationTache->realisationTache?->reference,
                'user_reference' => $historiqueRealisationTache->user?->reference,
                'reference' => $historiqueRealisationTache->reference,
                'isFeedback' => $historiqueRealisationTache->isFeedback ? '1' : '0',
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
