<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\RealisationChapitre;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationChapitreExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'chapitre_reference' => 'chapitre_reference',
                'etat_realisation_chapitre_reference' => 'etat_realisation_chapitre_reference',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'realisation_ua_reference' => 'realisation_ua_reference',
                'realisation_tache_reference' => 'realisation_tache_reference',
                'commentaire_formateur' => 'commentaire_formateur',
                'reference' => 'reference',
            ];
        } else {
            return [
                'chapitre_reference' => __('PkgApprentissage::realisationChapitre.chapitre_reference'),
                'etat_realisation_chapitre_reference' => __('PkgApprentissage::realisationChapitre.etat_realisation_chapitre_reference'),
                'date_debut' => __('PkgApprentissage::realisationChapitre.date_debut'),
                'date_fin' => __('PkgApprentissage::realisationChapitre.date_fin'),
                'realisation_ua_reference' => __('PkgApprentissage::realisationChapitre.realisation_ua_reference'),
                'realisation_tache_reference' => __('PkgApprentissage::realisationChapitre.realisation_tache_reference'),
                'commentaire_formateur' => __('PkgApprentissage::realisationChapitre.commentaire_formateur'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationChapitre) {
            return [
                'chapitre_reference' => $realisationChapitre->chapitre?->reference,
                'etat_realisation_chapitre_reference' => $realisationChapitre->etatRealisationChapitre?->reference,
                'date_debut' => $realisationChapitre->date_debut,
                'date_fin' => $realisationChapitre->date_fin,
                'realisation_ua_reference' => $realisationChapitre->realisationUa?->reference,
                'realisation_tache_reference' => $realisationChapitre->realisationTache?->reference,
                'commentaire_formateur' => $realisationChapitre->commentaire_formateur,
                'reference' => $realisationChapitre->reference,
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
