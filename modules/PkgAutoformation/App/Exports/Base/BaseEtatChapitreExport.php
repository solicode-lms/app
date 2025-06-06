<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Exports\Base;

use Modules\PkgAutoformation\Models\EtatChapitre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEtatChapitreExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'reference' => 'reference',
            'workflow_chapitre_id' => 'workflow_chapitre_id',
            'sys_color_id' => 'sys_color_id',
            'is_editable_only_by_formateur' => 'is_editable_only_by_formateur',
            'description' => 'description',
            'formateur_id' => 'formateur_id',
        ];
        }else{
        return [
            'nom' => __('PkgAutoformation::etatChapitre.nom'),
            'reference' => __('Core::msg.reference'),
            'workflow_chapitre_id' => __('PkgAutoformation::etatChapitre.workflow_chapitre_id'),
            'sys_color_id' => __('PkgAutoformation::etatChapitre.sys_color_id'),
            'is_editable_only_by_formateur' => __('PkgAutoformation::etatChapitre.is_editable_only_by_formateur'),
            'description' => __('PkgAutoformation::etatChapitre.description'),
            'formateur_id' => __('PkgAutoformation::etatChapitre.formateur_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($etatChapitre) {
            return [
                'nom' => $etatChapitre->nom,
                'reference' => $etatChapitre->reference,
                'workflow_chapitre_id' => $etatChapitre->workflow_chapitre_id,
                'sys_color_id' => $etatChapitre->sys_color_id,
                'is_editable_only_by_formateur' => $etatChapitre->is_editable_only_by_formateur,
                'description' => $etatChapitre->description,
                'formateur_id' => $etatChapitre->formateur_id,
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
