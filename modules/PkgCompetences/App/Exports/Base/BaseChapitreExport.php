<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Exports\Base;

use Modules\PkgCompetences\Models\Chapitre;
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
                'ordre' => 'ordre',
                'code' => 'code',
                'reference' => 'reference',
                'nom' => 'nom',
                'lien' => 'lien',
                'description' => 'description',
                'isOfficiel' => 'isOfficiel',
                'unite_apprentissage_reference' => 'unite_apprentissage_reference',
                'duree_en_heure' => 'duree_en_heure',
                'formateur_reference' => 'formateur_reference',
            ];
        } else {
            return [
                'ordre' => __('PkgCompetences::chapitre.ordre'),
                'code' => __('PkgCompetences::chapitre.code'),
                'reference' => __('Core::msg.reference'),
                'nom' => __('PkgCompetences::chapitre.nom'),
                'lien' => __('PkgCompetences::chapitre.lien'),
                'description' => __('PkgCompetences::chapitre.description'),
                'isOfficiel' => __('PkgCompetences::chapitre.isOfficiel'),
                'unite_apprentissage_reference' => __('PkgCompetences::chapitre.unite_apprentissage_reference'),
                'duree_en_heure' => __('PkgCompetences::chapitre.duree_en_heure'),
                'formateur_reference' => __('PkgCompetences::chapitre.formateur_reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($chapitre) {
            return [
                'ordre' => (string) $chapitre->ordre,
                'code' => $chapitre->code,
                'reference' => $chapitre->reference,
                'nom' => $chapitre->nom,
                'lien' => $chapitre->lien,
                'description' => $chapitre->description,
                'isOfficiel' => $chapitre->isOfficiel,
                'unite_apprentissage_reference' => $chapitre->uniteApprentissage?->reference,
                'duree_en_heure' => $chapitre->duree_en_heure,
                'formateur_reference' => $chapitre->formateur?->reference,
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
