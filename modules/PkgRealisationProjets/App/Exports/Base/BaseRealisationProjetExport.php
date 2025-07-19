<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Exports\Base;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'affectation_projet_reference' => 'affectation_projet_reference',
                'apprenant_reference' => 'apprenant_reference',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'etats_realisation_projet_reference' => 'etats_realisation_projet_reference',
                'rapport' => 'rapport',
                'reference' => 'reference',
            ];
        } else {
            return [
                'affectation_projet_reference' => __('PkgRealisationProjets::realisationProjet.affectation_projet_reference'),
                'apprenant_reference' => __('PkgRealisationProjets::realisationProjet.apprenant_reference'),
                'date_debut' => __('PkgRealisationProjets::realisationProjet.date_debut'),
                'date_fin' => __('PkgRealisationProjets::realisationProjet.date_fin'),
                'etats_realisation_projet_reference' => __('PkgRealisationProjets::realisationProjet.etats_realisation_projet_reference'),
                'rapport' => __('PkgRealisationProjets::realisationProjet.rapport'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationProjet) {
            return [
                'affectation_projet_reference' => $realisationProjet->affectationProjet?->reference,
                'apprenant_reference' => $realisationProjet->apprenant?->reference,
                'date_debut' => $realisationProjet->date_debut,
                'date_fin' => $realisationProjet->date_fin,
                'etats_realisation_projet_reference' => $realisationProjet->etatsRealisationProjet?->reference,
                'rapport' => $realisationProjet->rapport,
                'reference' => $realisationProjet->reference,
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
