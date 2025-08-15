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
    protected $format;

    public function __construct($data, $format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    /**
     * Génère les en-têtes du fichier exporté
     */
    public function headings(): array
    {
        if ($this->format === 'csv') {
            return [
                'projet_reference' => 'projet_reference',
                'groupe_reference' => 'groupe_reference',
                'sous_groupe_reference' => 'sous_groupe_reference',
                'annee_formation_reference' => 'annee_formation_reference',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'is_formateur_evaluateur' => 'is_formateur_evaluateur',
                'reference' => 'reference',
                'echelle_note_cible' => 'echelle_note_cible',
                'evaluateurs' => 'evaluateurs',
                'description' => 'description',
            ];
        } else {
            return [
                'projet_reference' => __('PkgCreationProjet::projet.singular'),
                'groupe_reference' => __('PkgApprenants::groupe.singular'),
                'sous_groupe_reference' => __('PkgApprenants::sousGroupe.singular'),
                'annee_formation_reference' => __('PkgFormation::anneeFormation.singular'),
                'date_debut' => __('PkgRealisationProjets::affectationProjet.date_debut'),
                'date_fin' => __('PkgRealisationProjets::affectationProjet.date_fin'),
                'is_formateur_evaluateur' => __('PkgRealisationProjets::affectationProjet.is_formateur_evaluateur'),
                'reference' => __('Core::msg.reference'),
                'echelle_note_cible' => __('PkgRealisationProjets::affectationProjet.echelle_note_cible'),
                    'evaluateurs' => __('PkgEvaluateurs::evaluateur.plural'),
                'description' => __('PkgRealisationProjets::affectationProjet.description'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($affectationProjet) {
            return [
                'projet_reference' => $affectationProjet->projet?->reference,
                'groupe_reference' => $affectationProjet->groupe?->reference,
                'sous_groupe_reference' => $affectationProjet->sousGroupe?->reference,
                'annee_formation_reference' => $affectationProjet->anneeFormation?->reference,
                'date_debut' => $affectationProjet->date_debut,
                'date_fin' => $affectationProjet->date_fin,
                'is_formateur_evaluateur' => $affectationProjet->is_formateur_evaluateur ? '1' : '0',
                'reference' => $affectationProjet->reference,
                'echelle_note_cible' => (string) $affectationProjet->echelle_note_cible,
                'evaluateurs' => $affectationProjet->evaluateurs
                    ->pluck('reference')
                    ->implode('|'),
                'description' => $affectationProjet->description,
            ];
        });
    }

    /**
     * Applique le style au fichier exporté
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Bordures pour toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Style spécifique pour les en-têtes
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Largeur automatique pour toutes les colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
