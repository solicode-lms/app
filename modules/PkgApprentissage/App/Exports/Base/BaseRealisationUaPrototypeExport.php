<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\RealisationUaPrototype;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationUaPrototypeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'realisation_ua_reference' => 'realisation_ua_reference',
                'bareme' => 'bareme',
                'note' => 'note',
                'remarque_formateur' => 'remarque_formateur',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'reference' => 'reference',
            ];
        } else {
            return [
                'realisation_tache_reference' => __('PkgRealisationTache::realisationTache.singular'),
                'realisation_ua_reference' => __('PkgApprentissage::realisationUa.singular'),
                'bareme' => __('PkgApprentissage::realisationUaPrototype.bareme'),
                'note' => __('PkgApprentissage::realisationUaPrototype.note'),
                'remarque_formateur' => __('PkgApprentissage::realisationUaPrototype.remarque_formateur'),
                'date_debut' => __('PkgApprentissage::realisationUaPrototype.date_debut'),
                'date_fin' => __('PkgApprentissage::realisationUaPrototype.date_fin'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationUaPrototype) {
            return [
                'realisation_tache_reference' => $realisationUaPrototype->realisationTache?->reference,
                'realisation_ua_reference' => $realisationUaPrototype->realisationUa?->reference,
                'bareme' => $realisationUaPrototype->bareme,
                'note' => $realisationUaPrototype->note,
                'remarque_formateur' => $realisationUaPrototype->remarque_formateur,
                'date_debut' => $realisationUaPrototype->date_debut,
                'date_fin' => $realisationUaPrototype->date_fin,
                'reference' => $realisationUaPrototype->reference,
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
