<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Exports\Base;

use Modules\PkgCreationProjet\Models\Livrable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseLivrableExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'nature_livrable_reference' => 'nature_livrable_reference',
                'titre' => 'titre',
                'projet_reference' => 'projet_reference',
                'description' => 'description',
                'reference' => 'reference',
                'is_affichable_seulement_par_formateur' => 'is_affichable_seulement_par_formateur',
            ];
        } else {
            return [
                'nature_livrable_reference' => __('PkgCreationProjet::livrable.nature_livrable_reference'),
                'titre' => __('PkgCreationProjet::livrable.titre'),
                'projet_reference' => __('PkgCreationProjet::livrable.projet_reference'),
                'description' => __('PkgCreationProjet::livrable.description'),
                'reference' => __('Core::msg.reference'),
                'is_affichable_seulement_par_formateur' => __('PkgCreationProjet::livrable.is_affichable_seulement_par_formateur'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($livrable) {
            return [
                'nature_livrable_reference' => $livrable->natureLivrable?->reference,
                'titre' => $livrable->titre,
                'projet_reference' => $livrable->projet?->reference,
                'description' => $livrable->description,
                'reference' => $livrable->reference,
                'is_affichable_seulement_par_formateur' => $livrable->is_affichable_seulement_par_formateur ? '1' : '0',
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
