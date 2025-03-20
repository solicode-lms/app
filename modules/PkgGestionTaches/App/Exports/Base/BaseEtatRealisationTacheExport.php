<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgGestionTaches\App\Exports\Base;

use Modules\PkgGestionTaches\Models\EtatRealisationTache;
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

    public function __construct($data,$format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    public function headings(): array
    {
     if($this->format == 'csv'){
        return [
            'nom' => 'nom',
            'workflow_tache_id' => 'workflow_tache_id',
            'sys_color_id' => 'sys_color_id',
            'is_editable_only_by_formateur' => 'is_editable_only_by_formateur',
            'reference' => 'reference',
            'formateur_id' => 'formateur_id',
            'description' => 'description',
        ];
        }else{
        return [
            'nom' => __('PkgGestionTaches::etatRealisationTache.nom'),
            'workflow_tache_id' => __('PkgGestionTaches::etatRealisationTache.workflow_tache_id'),
            'sys_color_id' => __('PkgGestionTaches::etatRealisationTache.sys_color_id'),
            'is_editable_only_by_formateur' => __('PkgGestionTaches::etatRealisationTache.is_editable_only_by_formateur'),
            'reference' => __('Core::msg.reference'),
            'formateur_id' => __('PkgGestionTaches::etatRealisationTache.formateur_id'),
            'description' => __('PkgGestionTaches::etatRealisationTache.description'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($etatRealisationTache) {
            return [
                'nom' => $etatRealisationTache->nom,
                'workflow_tache_id' => $etatRealisationTache->workflow_tache_id,
                'sys_color_id' => $etatRealisationTache->sys_color_id,
                'is_editable_only_by_formateur' => $etatRealisationTache->is_editable_only_by_formateur,
                'reference' => $etatRealisationTache->reference,
                'formateur_id' => $etatRealisationTache->formateur_id,
                'description' => $etatRealisationTache->description,
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Appliquer les bordures à toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Appliquer un style spécifique aux en-têtes (ligne 1)
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'], // Texte blanc
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'], // Fond bleu
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Ajuster automatiquement la largeur des colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
