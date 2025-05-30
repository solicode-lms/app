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

    public function __construct($data,$format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    public function headings(): array
    {
     if($this->format == 'csv'){
        return [
            'nature_livrable_id' => 'nature_livrable_id',
            'titre' => 'titre',
            'projet_id' => 'projet_id',
            'description' => 'description',
            'reference' => 'reference',
            'is_affichable_seulement_par_formateur' => 'is_affichable_seulement_par_formateur',
        ];
        }else{
        return [
            'nature_livrable_id' => __('PkgCreationProjet::livrable.nature_livrable_id'),
            'titre' => __('PkgCreationProjet::livrable.titre'),
            'projet_id' => __('PkgCreationProjet::livrable.projet_id'),
            'description' => __('PkgCreationProjet::livrable.description'),
            'reference' => __('Core::msg.reference'),
            'is_affichable_seulement_par_formateur' => __('PkgCreationProjet::livrable.is_affichable_seulement_par_formateur'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($livrable) {
            return [
                'nature_livrable_id' => $livrable->nature_livrable_id,
                'titre' => $livrable->titre,
                'projet_id' => $livrable->projet_id,
                'description' => $livrable->description,
                'reference' => $livrable->reference,
                'is_affichable_seulement_par_formateur' => $livrable->is_affichable_seulement_par_formateur,
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
