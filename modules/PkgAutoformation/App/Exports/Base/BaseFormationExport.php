<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgAutoformation\App\Exports\Base;

use Modules\PkgAutoformation\Models\Formation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseFormationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'competence_id' => 'competence_id',
            'is_officiel' => 'is_officiel',
            'reference' => 'reference',
            'formateur_id' => 'formateur_id',
            'formation_officiel_id' => 'formation_officiel_id',
            'description' => 'description',
        ];
        }else{
        return [
            'nom' => __('PkgAutoformation::formation.nom'),
            'lien' => __('PkgAutoformation::formation.lien'),
            'competence_id' => __('PkgAutoformation::formation.competence_id'),
            'is_officiel' => __('PkgAutoformation::formation.is_officiel'),
            'reference' => __('Core::msg.reference'),
            'formateur_id' => __('PkgAutoformation::formation.formateur_id'),
            'formation_officiel_id' => __('PkgAutoformation::formation.formation_officiel_id'),
            'description' => __('PkgAutoformation::formation.description'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($formation) {
            return [
                'nom' => $formation->nom,
                'lien' => $formation->lien,
                'competence_id' => $formation->competence_id,
                'is_officiel' => $formation->is_officiel,
                'reference' => $formation->reference,
                'formateur_id' => $formation->formateur_id,
                'formation_officiel_id' => $formation->formation_officiel_id,
                'description' => $formation->description,
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
