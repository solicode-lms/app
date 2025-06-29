<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Exports\Base;

use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseAffectationProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'projet_id' => 'projet_id',
            'groupe_id' => 'groupe_id',
            'annee_formation_id' => 'annee_formation_id',
            'date_debut' => 'date_debut',
            'date_fin' => 'date_fin',
            'sous_groupe_id' => 'sous_groupe_id',
            'is_formateur_evaluateur' => 'is_formateur_evaluateur',
            'reference' => 'reference',
            'echelle_note_cible' => 'echelle_note_cible',
            'description' => 'description',
        ];
        }else{
        return [
            'projet_id' => __('PkgRealisationProjets::affectationProjet.projet_id'),
            'groupe_id' => __('PkgRealisationProjets::affectationProjet.groupe_id'),
            'annee_formation_id' => __('PkgRealisationProjets::affectationProjet.annee_formation_id'),
            'date_debut' => __('PkgRealisationProjets::affectationProjet.date_debut'),
            'date_fin' => __('PkgRealisationProjets::affectationProjet.date_fin'),
            'sous_groupe_id' => __('PkgRealisationProjets::affectationProjet.sous_groupe_id'),
            'is_formateur_evaluateur' => __('PkgRealisationProjets::affectationProjet.is_formateur_evaluateur'),
            'reference' => __('Core::msg.reference'),
            'echelle_note_cible' => __('PkgRealisationProjets::affectationProjet.echelle_note_cible'),
            'description' => __('PkgRealisationProjets::affectationProjet.description'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($affectationProjet) {
            return [
                'projet_id' => $affectationProjet->projet_id,
                'groupe_id' => $affectationProjet->groupe_id,
                'annee_formation_id' => $affectationProjet->annee_formation_id,
                'date_debut' => $affectationProjet->date_debut,
                'date_fin' => $affectationProjet->date_fin,
                'sous_groupe_id' => $affectationProjet->sous_groupe_id,
                'is_formateur_evaluateur' => $affectationProjet->is_formateur_evaluateur,
                'reference' => $affectationProjet->reference,
                'echelle_note_cible' => $affectationProjet->echelle_note_cible,
                'description' => $affectationProjet->description,
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
