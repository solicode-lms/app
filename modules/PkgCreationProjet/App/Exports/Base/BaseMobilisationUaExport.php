<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Exports\Base;

use Modules\PkgCreationProjet\Models\MobilisationUa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseMobilisationUaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'unite_apprentissage_reference' => 'unite_apprentissage_reference',
                'bareme_evaluation_prototype' => 'bareme_evaluation_prototype',
                'criteres_evaluation_prototype' => 'criteres_evaluation_prototype',
                'bareme_evaluation_projet' => 'bareme_evaluation_projet',
                'criteres_evaluation_projet' => 'criteres_evaluation_projet',
                'description' => 'description',
                'projet_reference' => 'projet_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'unite_apprentissage_reference' => __('PkgCreationProjet::mobilisationUa.unite_apprentissage_reference'),
                'bareme_evaluation_prototype' => __('PkgCreationProjet::mobilisationUa.bareme_evaluation_prototype'),
                'criteres_evaluation_prototype' => __('PkgCreationProjet::mobilisationUa.criteres_evaluation_prototype'),
                'bareme_evaluation_projet' => __('PkgCreationProjet::mobilisationUa.bareme_evaluation_projet'),
                'criteres_evaluation_projet' => __('PkgCreationProjet::mobilisationUa.criteres_evaluation_projet'),
                'description' => __('PkgCreationProjet::mobilisationUa.description'),
                'projet_reference' => __('PkgCreationProjet::mobilisationUa.projet_reference'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($mobilisationUa) {
            return [
                'unite_apprentissage_reference' => $mobilisationUa->uniteApprentissage?->reference,
                'bareme_evaluation_prototype' => $mobilisationUa->bareme_evaluation_prototype,
                'criteres_evaluation_prototype' => $mobilisationUa->criteres_evaluation_prototype,
                'bareme_evaluation_projet' => $mobilisationUa->bareme_evaluation_projet,
                'criteres_evaluation_projet' => $mobilisationUa->criteres_evaluation_projet,
                'description' => $mobilisationUa->description,
                'projet_reference' => $mobilisationUa->projet?->reference,
                'reference' => $mobilisationUa->reference,
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
