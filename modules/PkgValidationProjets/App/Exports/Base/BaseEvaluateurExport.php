<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgValidationProjets\App\Exports\Base;

use Modules\PkgValidationProjets\Models\Evaluateur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEvaluateurExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'reference' => 'reference',
            'nom' => 'nom',
            'prenom' => 'prenom',
            'email' => 'email',
            'telephone' => 'telephone',
            'organism' => 'organism',
            'formateur_id' => 'formateur_id',
        ];
        }else{
        return [
            'reference' => __('Core::msg.reference'),
            'nom' => __('PkgValidationProjets::evaluateur.nom'),
            'prenom' => __('PkgValidationProjets::evaluateur.prenom'),
            'email' => __('PkgValidationProjets::evaluateur.email'),
            'telephone' => __('PkgValidationProjets::evaluateur.telephone'),
            'organism' => __('PkgValidationProjets::evaluateur.organism'),
            'formateur_id' => __('PkgValidationProjets::evaluateur.formateur_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($evaluateur) {
            return [
                'reference' => $evaluateur->reference,
                'nom' => $evaluateur->nom,
                'prenom' => $evaluateur->prenom,
                'email' => $evaluateur->email,
                'telephone' => $evaluateur->telephone,
                'organism' => $evaluateur->organism,
                'formateur_id' => $evaluateur->formateur_id,
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
