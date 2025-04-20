<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Exports\Base;

use Modules\PkgCreationProjet\Models\Projet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'titre' => 'titre',
            'formateur_id' => 'formateur_id',
            'travail_a_faire' => 'travail_a_faire',
            'critere_de_travail' => 'critere_de_travail',
            'nombre_jour' => 'nombre_jour',
            'filiere_id' => 'filiere_id',
            'description' => 'description',
            'reference' => 'reference',
        ];
        }else{
        return [
            'titre' => __('PkgCreationProjet::projet.titre'),
            'formateur_id' => __('PkgCreationProjet::projet.formateur_id'),
            'travail_a_faire' => __('PkgCreationProjet::projet.travail_a_faire'),
            'critere_de_travail' => __('PkgCreationProjet::projet.critere_de_travail'),
            'nombre_jour' => __('PkgCreationProjet::projet.nombre_jour'),
            'filiere_id' => __('PkgCreationProjet::projet.filiere_id'),
            'description' => __('PkgCreationProjet::projet.description'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($projet) {
            return [
                'titre' => $projet->titre,
                'formateur_id' => $projet->formateur_id,
                'travail_a_faire' => $projet->travail_a_faire,
                'critere_de_travail' => $projet->critere_de_travail,
                'nombre_jour' => $projet->nombre_jour,
                'filiere_id' => $projet->filiere_id,
                'description' => $projet->description,
                'reference' => $projet->reference,
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
