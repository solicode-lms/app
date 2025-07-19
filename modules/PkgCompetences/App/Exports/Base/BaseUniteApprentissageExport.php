<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCompetences\App\Exports\Base;

use Modules\PkgCompetences\Models\UniteApprentissage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseUniteApprentissageExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'nom' => 'nom',
                'lien' => 'lien',
                'description' => 'description',
                'micro_competence_reference' => 'micro_competence_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'ordre' => __('PkgCompetences::uniteApprentissage.ordre'),
                'code' => __('PkgCompetences::uniteApprentissage.code'),
                'nom' => __('PkgCompetences::uniteApprentissage.nom'),
                'lien' => __('PkgCompetences::uniteApprentissage.lien'),
                'description' => __('PkgCompetences::uniteApprentissage.description'),
                'micro_competence_reference' => __('PkgCompetences::uniteApprentissage.micro_competence_reference'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($uniteApprentissage) {
            return [
                'ordre' => $uniteApprentissage->ordre,
                'code' => $uniteApprentissage->code,
                'nom' => $uniteApprentissage->nom,
                'lien' => $uniteApprentissage->lien,
                'description' => $uniteApprentissage->description,
                'micro_competence_reference' => $uniteApprentissage->microCompetence?->reference,
                'reference' => $uniteApprentissage->reference,
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
