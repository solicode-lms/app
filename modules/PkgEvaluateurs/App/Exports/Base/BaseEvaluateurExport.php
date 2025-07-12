<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgEvaluateurs\App\Exports\Base;

use Modules\PkgEvaluateurs\Models\Evaluateur;
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
            'nom' => 'nom',
            'prenom' => 'prenom',
            'email' => 'email',
            'organism' => 'organism',
            'telephone' => 'telephone',
            'user_id' => 'user_id',
            'reference' => 'reference',
        ];
        }else{
        return [
            'nom' => __('PkgEvaluateurs::evaluateur.nom'),
            'prenom' => __('PkgEvaluateurs::evaluateur.prenom'),
            'email' => __('PkgEvaluateurs::evaluateur.email'),
            'organism' => __('PkgEvaluateurs::evaluateur.organism'),
            'telephone' => __('PkgEvaluateurs::evaluateur.telephone'),
            'user_id' => __('PkgEvaluateurs::evaluateur.user_id'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($evaluateur) {
            return [
                'nom' => $evaluateur->nom,
                'prenom' => $evaluateur->prenom,
                'email' => $evaluateur->email,
                'organism' => $evaluateur->organism,
                'telephone' => $evaluateur->telephone,
                'user_id' => $evaluateur->user_id,
                'reference' => $evaluateur->reference,
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
