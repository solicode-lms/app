<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Exports\Base;

use Modules\PkgApprenants\Models\Groupe;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseGroupeExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'code' => 'code',
                'nom' => 'nom',
                'description' => 'description',
                'filiere_reference' => 'filiere_reference',
                'annee_formation_reference' => 'annee_formation_reference',
                'reference' => 'reference',
                'apprenants' => 'apprenants',
                'formateurs' => 'formateurs',
            ];
        } else {
            return [
                'code' => __('PkgApprenants::groupe.code'),
                'nom' => __('PkgApprenants::groupe.nom'),
                'description' => __('PkgApprenants::groupe.description'),
                'filiere_reference' => __('PkgFormation::filiere.singular'),
                'annee_formation_reference' => __('PkgFormation::anneeFormation.singular'),
                'reference' => __('Core::msg.reference'),
                    'apprenants' => __('PkgApprenants::apprenant.plural'),
                    'formateurs' => __('PkgFormation::formateur.plural'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($groupe) {
            return [
                'code' => $groupe->code,
                'nom' => $groupe->nom,
                'description' => $groupe->description,
                'filiere_reference' => $groupe->filiere?->reference,
                'annee_formation_reference' => $groupe->anneeFormation?->reference,
                'reference' => $groupe->reference,
                'apprenants' => $groupe->apprenants
                    ->pluck('reference')
                    ->implode('|'),
                'formateurs' => $groupe->formateurs
                    ->pluck('reference')
                    ->implode('|'),
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
