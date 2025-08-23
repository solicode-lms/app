<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\RealisationUa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationUaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'unite_apprentissage_reference' => 'unite_apprentissage_reference',
                'realisation_micro_competence_reference' => 'realisation_micro_competence_reference',
                'etat_realisation_ua_reference' => 'etat_realisation_ua_reference',
                'progression_cache' => 'progression_cache',
                'note_cache' => 'note_cache',
                'bareme_cache' => 'bareme_cache',
                'dernier_update' => 'dernier_update',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'commentaire_formateur' => 'commentaire_formateur',
                'reference' => 'reference',
                'progression_ideal_cache' => 'progression_ideal_cache',
                'taux_rythme_cache' => 'taux_rythme_cache',
            ];
        } else {
            return [
                'unite_apprentissage_reference' => __('PkgCompetences::uniteApprentissage.singular'),
                'realisation_micro_competence_reference' => __('PkgApprentissage::realisationMicroCompetence.singular'),
                'etat_realisation_ua_reference' => __('PkgApprentissage::etatRealisationUa.singular'),
                'progression_cache' => __('PkgApprentissage::realisationUa.progression_cache'),
                'note_cache' => __('PkgApprentissage::realisationUa.note_cache'),
                'bareme_cache' => __('PkgApprentissage::realisationUa.bareme_cache'),
                'dernier_update' => __('PkgApprentissage::realisationUa.dernier_update'),
                'date_debut' => __('PkgApprentissage::realisationUa.date_debut'),
                'date_fin' => __('PkgApprentissage::realisationUa.date_fin'),
                'commentaire_formateur' => __('PkgApprentissage::realisationUa.commentaire_formateur'),
                'reference' => __('Core::msg.reference'),
                'progression_ideal_cache' => __('PkgApprentissage::realisationUa.progression_ideal_cache'),
                'taux_rythme_cache' => __('PkgApprentissage::realisationUa.taux_rythme_cache'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationUa) {
            return [
                'unite_apprentissage_reference' => $realisationUa->uniteApprentissage?->reference,
                'realisation_micro_competence_reference' => $realisationUa->realisationMicroCompetence?->reference,
                'etat_realisation_ua_reference' => $realisationUa->etatRealisationUa?->reference,
                'progression_cache' => $realisationUa->progression_cache,
                'note_cache' => $realisationUa->note_cache,
                'bareme_cache' => $realisationUa->bareme_cache,
                'dernier_update' => $realisationUa->dernier_update,
                'date_debut' => $realisationUa->date_debut,
                'date_fin' => $realisationUa->date_fin,
                'commentaire_formateur' => $realisationUa->commentaire_formateur,
                'reference' => $realisationUa->reference,
                'progression_ideal_cache' => $realisationUa->progression_ideal_cache,
                'taux_rythme_cache' => $realisationUa->taux_rythme_cache,
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
