<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgTableauBord\App\Exports\Base;

use Modules\PkgTableauBord\Models\BordereauxOfficiel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseBordereauxOfficielExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'module_reference' => 'module_reference',
                'groupe_reference' => 'groupe_reference',
                'annee_formation_reference' => 'annee_formation_reference',
                'nb_apprenants' => 'nb_apprenants',
                'taux_conformite_calcul' => 'taux_conformite_calcul',
                'statut' => 'statut',
                'date_scellement' => 'date_scellement',
                'hash_signature' => 'hash_signature',
                'genere_par_user_reference' => 'user_reference',
            ];
        } else {
            return [
                'reference' => __('Core::msg.reference'),
                'module_reference' => __('PkgFormation::module.singular'),
                'groupe_reference' => __('PkgApprenants::groupe.singular'),
                'annee_formation_reference' => __('PkgFormation::anneeFormation.singular'),
                'nb_apprenants' => __('PkgTableauBord::bordereauxOfficiel.nb_apprenants'),
                'taux_conformite_calcul' => __('PkgTableauBord::bordereauxOfficiel.taux_conformite_calcul'),
                'statut' => __('PkgTableauBord::bordereauxOfficiel.statut'),
                'date_scellement' => __('PkgTableauBord::bordereauxOfficiel.date_scellement'),
                'hash_signature' => __('PkgTableauBord::bordereauxOfficiel.hash_signature'),
                'genere_par_user_reference' => __('PkgAutorisation::user.singular'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($bordereauxOfficiel) {
            return [
                'reference' => $bordereauxOfficiel->reference,
                'module_reference' => $bordereauxOfficiel->module?->reference,
                'groupe_reference' => $bordereauxOfficiel->groupe?->reference,
                'annee_formation_reference' => $bordereauxOfficiel->anneeFormation?->reference,
                'nb_apprenants' => (string) $bordereauxOfficiel->nb_apprenants,
                'taux_conformite_calcul' => $bordereauxOfficiel->taux_conformite_calcul,
                'statut' => $bordereauxOfficiel->statut,
                'date_scellement' => $bordereauxOfficiel->date_scellement,
                'hash_signature' => $bordereauxOfficiel->hash_signature,
                'user_reference' => $bordereauxOfficiel->genereParUser?->reference,
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
