<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Exports\Base;

use Modules\PkgAutoformation\Models\Chapitre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseChapitreExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'lien' => 'lien',
            'coefficient' => 'coefficient',
            'description' => 'description',
            'ordre' => 'ordre',
            'is_officiel' => 'is_officiel',
            'reference' => 'reference',
            'formation_id' => 'formation_id',
            'niveau_competence_id' => 'niveau_competence_id',
            'formateur_id' => 'formateur_id',
            'chapitre_officiel_id' => 'chapitre_officiel_id',
        ];
        }else{
        return [
            'nom' => __('PkgAutoformation::chapitre.nom'),
            'lien' => __('PkgAutoformation::chapitre.lien'),
            'coefficient' => __('PkgAutoformation::chapitre.coefficient'),
            'description' => __('PkgAutoformation::chapitre.description'),
            'ordre' => __('PkgAutoformation::chapitre.ordre'),
            'is_officiel' => __('PkgAutoformation::chapitre.is_officiel'),
            'reference' => __('Core::msg.reference'),
            'formation_id' => __('PkgAutoformation::chapitre.formation_id'),
            'niveau_competence_id' => __('PkgAutoformation::chapitre.niveau_competence_id'),
            'formateur_id' => __('PkgAutoformation::chapitre.formateur_id'),
            'chapitre_officiel_id' => __('PkgAutoformation::chapitre.chapitre_officiel_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($chapitre) {
            return [
                'nom' => $chapitre->nom,
                'lien' => $chapitre->lien,
                'coefficient' => $chapitre->coefficient,
                'description' => $chapitre->description,
                'ordre' => $chapitre->ordre,
                'is_officiel' => $chapitre->is_officiel,
                'reference' => $chapitre->reference,
                'formation_id' => $chapitre->formation_id,
                'niveau_competence_id' => $chapitre->niveau_competence_id,
                'formateur_id' => $chapitre->formateur_id,
                'chapitre_officiel_id' => $chapitre->chapitre_officiel_id,
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
