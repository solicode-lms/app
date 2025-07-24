<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\RealisationMicroCompetence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationMicroCompetenceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'reference' => 'reference',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'progression_cache' => 'progression_cache',
                'note_cache' => 'note_cache',
                'bareme_cache' => 'bareme_cache',
                'commentaire_formateur' => 'commentaire_formateur',
                'dernier_update' => 'dernier_update',
                'apprenant_reference' => 'apprenant_reference',
                'micro_competence_reference' => 'micro_competence_reference',
                'etat_realisation_micro_competence_reference' => 'etat_realisation_micro_competence_reference',
            ];
        } else {
            return [
                'reference' => __('Core::msg.reference'),
                'date_debut' => __('PkgApprentissage::realisationMicroCompetence.date_debut'),
                'date_fin' => __('PkgApprentissage::realisationMicroCompetence.date_fin'),
                'progression_cache' => __('PkgApprentissage::realisationMicroCompetence.progression_cache'),
                'note_cache' => __('PkgApprentissage::realisationMicroCompetence.note_cache'),
                'bareme_cache' => __('PkgApprentissage::realisationMicroCompetence.bareme_cache'),
                'commentaire_formateur' => __('PkgApprentissage::realisationMicroCompetence.commentaire_formateur'),
                'dernier_update' => __('PkgApprentissage::realisationMicroCompetence.dernier_update'),
                'apprenant_reference' => __('PkgApprentissage::realisationMicroCompetence.apprenant_reference'),
                'micro_competence_reference' => __('PkgApprentissage::realisationMicroCompetence.micro_competence_reference'),
                'etat_realisation_micro_competence_reference' => __('PkgApprentissage::realisationMicroCompetence.etat_realisation_micro_competence_reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationMicroCompetence) {
            return [
                'reference' => $realisationMicroCompetence->reference,
                'date_debut' => $realisationMicroCompetence->date_debut,
                'date_fin' => $realisationMicroCompetence->date_fin,
                'progression_cache' => $realisationMicroCompetence->progression_cache,
                'note_cache' => $realisationMicroCompetence->note_cache,
                'bareme_cache' => $realisationMicroCompetence->bareme_cache,
                'commentaire_formateur' => $realisationMicroCompetence->commentaire_formateur,
                'dernier_update' => $realisationMicroCompetence->dernier_update,
                'apprenant_reference' => $realisationMicroCompetence->apprenant?->reference,
                'micro_competence_reference' => $realisationMicroCompetence->microCompetence?->reference,
                'etat_realisation_micro_competence_reference' => $realisationMicroCompetence->etatRealisationMicroCompetence?->reference,
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
