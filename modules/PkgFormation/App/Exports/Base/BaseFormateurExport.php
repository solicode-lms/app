<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgFormation\App\Exports\Base;

use Modules\PkgFormation\Models\Formateur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseFormateurExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'matricule' => 'matricule',
                'nom' => 'nom',
                'prenom' => 'prenom',
                'prenom_arab' => 'prenom_arab',
                'nom_arab' => 'nom_arab',
                'email' => 'email',
                'tele_num' => 'tele_num',
                'adresse' => 'adresse',
                'diplome' => 'diplome',
                'echelle' => 'echelle',
                'echelon' => 'echelon',
                'profile_image' => 'profile_image',
                'user_reference' => 'user_reference',
                'reference' => 'reference',
            ];
        } else {
            return [
                'matricule' => __('PkgFormation::formateur.matricule'),
                'nom' => __('PkgFormation::formateur.nom'),
                'prenom' => __('PkgFormation::formateur.prenom'),
                'prenom_arab' => __('PkgFormation::formateur.prenom_arab'),
                'nom_arab' => __('PkgFormation::formateur.nom_arab'),
                'email' => __('PkgFormation::formateur.email'),
                'tele_num' => __('PkgFormation::formateur.tele_num'),
                'adresse' => __('PkgFormation::formateur.adresse'),
                'diplome' => __('PkgFormation::formateur.diplome'),
                'echelle' => __('PkgFormation::formateur.echelle'),
                'echelon' => __('PkgFormation::formateur.echelon'),
                'profile_image' => __('PkgFormation::formateur.profile_image'),
                'user_reference' => __('PkgFormation::formateur.user_reference'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($formateur) {
            return [
                'matricule' => $formateur->matricule,
                'nom' => $formateur->nom,
                'prenom' => $formateur->prenom,
                'prenom_arab' => $formateur->prenom_arab,
                'nom_arab' => $formateur->nom_arab,
                'email' => $formateur->email,
                'tele_num' => $formateur->tele_num,
                'adresse' => $formateur->adresse,
                'diplome' => $formateur->diplome,
                'echelle' => (string) $formateur->echelle,
                'echelon' => (string) $formateur->echelon,
                'profile_image' => $formateur->profile_image,
                'user_reference' => $formateur->user?->reference,
                'reference' => $formateur->reference,
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
