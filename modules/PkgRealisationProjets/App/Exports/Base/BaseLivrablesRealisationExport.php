<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Exports\Base;

use Modules\PkgRealisationProjets\Models\LivrablesRealisation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseLivrablesRealisationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'livrable_reference' => 'livrable_reference',
                'lien' => 'lien',
                'titre' => 'titre',
                'description' => 'description',
                'realisation_projet_reference' => 'realisation_projet_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'livrable_reference' => __('PkgRealisationProjets::livrablesRealisation.livrable_reference'),
                'lien' => __('PkgRealisationProjets::livrablesRealisation.lien'),
                'titre' => __('PkgRealisationProjets::livrablesRealisation.titre'),
                'description' => __('PkgRealisationProjets::livrablesRealisation.description'),
                'realisation_projet_reference' => __('PkgRealisationProjets::livrablesRealisation.realisation_projet_reference'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($livrablesRealisation) {
            return [
                'livrable_reference' => $livrablesRealisation->livrable?->reference,
                'lien' => $livrablesRealisation->lien,
                'titre' => $livrablesRealisation->titre,
                'description' => $livrablesRealisation->description,
                'realisation_projet_reference' => $livrablesRealisation->realisationProjet?->reference,
                'reference' => $livrablesRealisation->reference,
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
