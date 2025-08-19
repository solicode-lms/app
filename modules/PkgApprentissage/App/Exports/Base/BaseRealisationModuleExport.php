<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprentissage\App\Exports\Base;

use Modules\PkgApprentissage\Models\RealisationModule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseRealisationModuleExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'module_reference' => 'module_reference',
                'apprenant_reference' => 'apprenant_reference',
                'progression_cache' => 'progression_cache',
                'etat_realisation_module_reference' => 'etat_realisation_module_reference',
                'note_cache' => 'note_cache',
                'bareme_cache' => 'bareme_cache',
                'dernier_update' => 'dernier_update',
                'commentaire_formateur' => 'commentaire_formateur',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'reference' => 'reference',
                'progression_ideal_cache' => 'progression_ideal_cache',
                'taux_rythme_cache' => 'taux_rythme_cache',
            ];
        } else {
            return [
                'module_reference' => __('PkgFormation::module.singular'),
                'apprenant_reference' => __('PkgApprenants::apprenant.singular'),
                'progression_cache' => __('PkgApprentissage::realisationModule.progression_cache'),
                'etat_realisation_module_reference' => __('PkgApprentissage::etatRealisationModule.singular'),
                'note_cache' => __('PkgApprentissage::realisationModule.note_cache'),
                'bareme_cache' => __('PkgApprentissage::realisationModule.bareme_cache'),
                'dernier_update' => __('PkgApprentissage::realisationModule.dernier_update'),
                'commentaire_formateur' => __('PkgApprentissage::realisationModule.commentaire_formateur'),
                'date_debut' => __('PkgApprentissage::realisationModule.date_debut'),
                'date_fin' => __('PkgApprentissage::realisationModule.date_fin'),
                'reference' => __('Core::msg.reference'),
                'progression_ideal_cache' => __('PkgApprentissage::realisationModule.progression_ideal_cache'),
                'taux_rythme_cache' => __('PkgApprentissage::realisationModule.taux_rythme_cache'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($realisationModule) {
            return [
                'module_reference' => $realisationModule->module?->reference,
                'apprenant_reference' => $realisationModule->apprenant?->reference,
                'progression_cache' => $realisationModule->progression_cache,
                'etat_realisation_module_reference' => $realisationModule->etatRealisationModule?->reference,
                'note_cache' => $realisationModule->note_cache,
                'bareme_cache' => $realisationModule->bareme_cache,
                'dernier_update' => $realisationModule->dernier_update,
                'commentaire_formateur' => $realisationModule->commentaire_formateur,
                'date_debut' => $realisationModule->date_debut,
                'date_fin' => $realisationModule->date_fin,
                'reference' => $realisationModule->reference,
                'progression_ideal_cache' => $realisationModule->progression_ideal_cache,
                'taux_rythme_cache' => $realisationModule->taux_rythme_cache,
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
