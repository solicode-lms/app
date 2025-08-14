<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\RealisationTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'tache_reference' => 'tache_reference',
                'etat_realisation_tache_reference' => 'etat_realisation_tache_reference',
                'realisation_projet_reference' => 'realisation_projet_reference',
                'dateDebut' => 'dateDebut',
                'dateFin' => 'dateFin',
                'remarque_evaluateur' => 'remarque_evaluateur',
                'note' => 'note',
                'is_live_coding' => 'is_live_coding',
                'remarques_formateur' => 'remarques_formateur',
                'remarques_apprenant' => 'remarques_apprenant',
                'tache_affectation_reference' => 'tache_affectation_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'tache_reference' => __('PkgRealisationTache::realisationTache.tache_reference'),
                'etat_realisation_tache_reference' => __('PkgRealisationTache::realisationTache.etat_realisation_tache_reference'),
                'realisation_projet_reference' => __('PkgRealisationTache::realisationTache.realisation_projet_reference'),
                'dateDebut' => __('PkgRealisationTache::realisationTache.dateDebut'),
                'dateFin' => __('PkgRealisationTache::realisationTache.dateFin'),
                'remarque_evaluateur' => __('PkgRealisationTache::realisationTache.remarque_evaluateur'),
                'note' => __('PkgRealisationTache::realisationTache.note'),
                'is_live_coding' => __('PkgRealisationTache::realisationTache.is_live_coding'),
                'remarques_formateur' => __('PkgRealisationTache::realisationTache.remarques_formateur'),
                'remarques_apprenant' => __('PkgRealisationTache::realisationTache.remarques_apprenant'),
                'tache_affectation_reference' => __('PkgRealisationTache::realisationTache.tache_affectation_reference'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationTache) {
            return [
                'tache_reference' => $realisationTache->tache?->reference,
                'etat_realisation_tache_reference' => $realisationTache->etatRealisationTache?->reference,
                'realisation_projet_reference' => $realisationTache->realisationProjet?->reference,
                'dateDebut' => $realisationTache->dateDebut,
                'dateFin' => $realisationTache->dateFin,
                'remarque_evaluateur' => $realisationTache->remarque_evaluateur,
                'note' => $realisationTache->note,
                'is_live_coding' => $realisationTache->is_live_coding ? '1' : '0',
                'remarques_formateur' => $realisationTache->remarques_formateur,
                'remarques_apprenant' => $realisationTache->remarques_apprenant,
                'tache_affectation_reference' => $realisationTache->tacheAffectation?->reference,
                'reference' => $realisationTache->reference,
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
