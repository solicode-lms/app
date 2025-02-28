<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Exports\Base;

use Modules\PkgRealisationProjets\Models\EtatsRealisationProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseEtatsRealisationProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'formateur_id' => 'formateur_id',
            'titre' => 'titre',
            'description' => 'description',
            'reference' => 'reference',
            'is_editable_by_formateur' => 'is_editable_by_formateur',
        ];
        }else{
        return [
            'formateur_id' => __('PkgRealisationProjets::etatsRealisationProjet.formateur_id'),
            'titre' => __('PkgRealisationProjets::etatsRealisationProjet.titre'),
            'description' => __('PkgRealisationProjets::etatsRealisationProjet.description'),
            'reference' => __('Core::msg.reference'),
            'is_editable_by_formateur' => __('PkgRealisationProjets::etatsRealisationProjet.is_editable_by_formateur'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($etatsRealisationProjet) {
            return [
                'formateur_id' => $etatsRealisationProjet->formateur_id,
                'titre' => $etatsRealisationProjet->titre,
                'description' => $etatsRealisationProjet->description,
                'reference' => $etatsRealisationProjet->reference,
                'is_editable_by_formateur' => $etatsRealisationProjet->is_editable_by_formateur,
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
