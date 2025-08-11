<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\RealisationCompetence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationCompetenceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'competence_reference' => 'competence_reference',
                'realisation_module_reference' => 'realisation_module_reference',
                'apprenant_reference' => 'apprenant_reference',
                'progression_cache' => 'progression_cache',
                'note_cache' => 'note_cache',
                'etat_realisation_competence_reference' => 'etat_realisation_competence_reference',
                'bareme_cache' => 'bareme_cache',
                'dernier_update' => 'dernier_update',
                'commentaire_formateur' => 'commentaire_formateur',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'reference' => 'reference',
            ];
        } else {
            return [
                'competence_reference' => __('PkgApprentissage::realisationCompetence.competence_reference'),
                'realisation_module_reference' => __('PkgApprentissage::realisationCompetence.realisation_module_reference'),
                'apprenant_reference' => __('PkgApprentissage::realisationCompetence.apprenant_reference'),
                'progression_cache' => __('PkgApprentissage::realisationCompetence.progression_cache'),
                'note_cache' => __('PkgApprentissage::realisationCompetence.note_cache'),
                'etat_realisation_competence_reference' => __('PkgApprentissage::realisationCompetence.etat_realisation_competence_reference'),
                'bareme_cache' => __('PkgApprentissage::realisationCompetence.bareme_cache'),
                'dernier_update' => __('PkgApprentissage::realisationCompetence.dernier_update'),
                'commentaire_formateur' => __('PkgApprentissage::realisationCompetence.commentaire_formateur'),
                'date_debut' => __('PkgApprentissage::realisationCompetence.date_debut'),
                'date_fin' => __('PkgApprentissage::realisationCompetence.date_fin'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationCompetence) {
            return [
                'competence_reference' => $realisationCompetence->competence?->reference,
                'realisation_module_reference' => $realisationCompetence->realisationModule?->reference,
                'apprenant_reference' => $realisationCompetence->apprenant?->reference,
                'progression_cache' => $realisationCompetence->progression_cache,
                'note_cache' => $realisationCompetence->note_cache,
                'etat_realisation_competence_reference' => $realisationCompetence->etatRealisationCompetence?->reference,
                'bareme_cache' => $realisationCompetence->bareme_cache,
                'dernier_update' => $realisationCompetence->dernier_update,
                'commentaire_formateur' => $realisationCompetence->commentaire_formateur,
                'date_debut' => $realisationCompetence->date_debut,
                'date_fin' => $realisationCompetence->date_fin,
                'reference' => $realisationCompetence->reference,
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
