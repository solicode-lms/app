<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Exports\Base;

use Modules\PkgValidationProjets\Models\EtatEvaluationProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEtatEvaluationProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'ordre' => 'ordre',
            'code' => 'code',
            'titre' => 'titre',
            'description' => 'description',
            'reference' => 'reference',
            'sys_color_id' => 'sys_color_id',
        ];
        }else{
        return [
            'ordre' => __('PkgValidationProjets::etatEvaluationProjet.ordre'),
            'code' => __('PkgValidationProjets::etatEvaluationProjet.code'),
            'titre' => __('PkgValidationProjets::etatEvaluationProjet.titre'),
            'description' => __('PkgValidationProjets::etatEvaluationProjet.description'),
            'reference' => __('Core::msg.reference'),
            'sys_color_id' => __('PkgValidationProjets::etatEvaluationProjet.sys_color_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($etatEvaluationProjet) {
            return [
                'ordre' => $etatEvaluationProjet->ordre,
                'code' => $etatEvaluationProjet->code,
                'titre' => $etatEvaluationProjet->titre,
                'description' => $etatEvaluationProjet->description,
                'reference' => $etatEvaluationProjet->reference,
                'sys_color_id' => $etatEvaluationProjet->sys_color_id,
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
