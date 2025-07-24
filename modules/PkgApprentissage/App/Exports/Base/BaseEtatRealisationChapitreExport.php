<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\EtatRealisationChapitre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEtatRealisationChapitreExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'nom' => 'nom',
                'code' => 'code',
                'description' => 'description',
                'is_editable_only_by_formateur' => 'is_editable_only_by_formateur',
                'sys_color_reference' => 'sys_color_reference',
            ];
        } else {
            return [
                'reference' => __('Core::msg.reference'),
                'nom' => __('PkgApprentissage::etatRealisationChapitre.nom'),
                'code' => __('PkgApprentissage::etatRealisationChapitre.code'),
                'description' => __('PkgApprentissage::etatRealisationChapitre.description'),
                'is_editable_only_by_formateur' => __('PkgApprentissage::etatRealisationChapitre.is_editable_only_by_formateur'),
                'sys_color_reference' => __('PkgApprentissage::etatRealisationChapitre.sys_color_reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($etatRealisationChapitre) {
            return [
                'reference' => $etatRealisationChapitre->reference,
                'nom' => $etatRealisationChapitre->nom,
                'code' => $etatRealisationChapitre->code,
                'description' => $etatRealisationChapitre->description,
                'is_editable_only_by_formateur' => $etatRealisationChapitre->is_editable_only_by_formateur,
                'sys_color_reference' => $etatRealisationChapitre->sysColor?->reference,
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
