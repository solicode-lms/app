<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEtatRealisationTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'nom' => 'nom',
                'workflow_tache_reference' => 'workflow_tache_reference',
                'sys_color_reference' => 'sys_color_reference',
                'is_editable_only_by_formateur' => 'is_editable_only_by_formateur',
                'reference' => 'reference',
                'formateur_reference' => 'formateur_reference',
                'description' => 'description',
            ];
        } else {
            return [
                'ordre' => __('PkgRealisationTache::etatRealisationTache.ordre'),
                'nom' => __('PkgRealisationTache::etatRealisationTache.nom'),
                'workflow_tache_reference' => __('PkgRealisationTache::etatRealisationTache.workflow_tache_reference'),
                'sys_color_reference' => __('PkgRealisationTache::etatRealisationTache.sys_color_reference'),
                'is_editable_only_by_formateur' => __('PkgRealisationTache::etatRealisationTache.is_editable_only_by_formateur'),
                'reference' => __('Core::msg.reference'),
                'formateur_reference' => __('PkgRealisationTache::etatRealisationTache.formateur_reference'),
                'description' => __('PkgRealisationTache::etatRealisationTache.description'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($etatRealisationTache) {
            return [
                'ordre' => (string) $etatRealisationTache->ordre,
                'nom' => $etatRealisationTache->nom,
                'workflow_tache_reference' => $etatRealisationTache->workflowTache?->reference,
                'sys_color_reference' => $etatRealisationTache->sysColor?->reference,
                'is_editable_only_by_formateur' => $etatRealisationTache->is_editable_only_by_formateur ? '1' : '0',
                'reference' => $etatRealisationTache->reference,
                'formateur_reference' => $etatRealisationTache->formateur?->reference,
                'description' => $etatRealisationTache->description,
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
