<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Exports\Base;

use Modules\PkgApprenants\Models\Apprenant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseApprenantExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'nom' => __('PkgApprenants::apprenant.nom'),
            'prenom' => __('PkgApprenants::apprenant.prenom'),
            'prenom_arab' => __('PkgApprenants::apprenant.prenom_arab'),
            'nom_arab' => __('PkgApprenants::apprenant.nom_arab'),
            'tele_num' => __('PkgApprenants::apprenant.tele_num'),
            'profile_image' => __('PkgApprenants::apprenant.profile_image'),
            'matricule' => __('PkgApprenants::apprenant.matricule'),
            'sexe' => __('PkgApprenants::apprenant.sexe'),
            'actif' => __('PkgApprenants::apprenant.actif'),
            'diplome' => __('PkgApprenants::apprenant.diplome'),
            'date_naissance' => __('PkgApprenants::apprenant.date_naissance'),
            'date_inscription' => __('PkgApprenants::apprenant.date_inscription'),
            'lieu_naissance' => __('PkgApprenants::apprenant.lieu_naissance'),
            'cin' => __('PkgApprenants::apprenant.cin'),
            'adresse' => __('PkgApprenants::apprenant.adresse'),
            'niveaux_scolaire_id' => __('PkgApprenants::apprenant.niveaux_scolaire_id'),
            'nationalite_id' => __('PkgApprenants::apprenant.nationalite_id'),
            'user_id' => __('PkgApprenants::apprenant.user_id'),
            'reference' => __('Core::msg.reference'),
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($apprenant) {
            return [
                'nom' => $apprenant->nom,
                'prenom' => $apprenant->prenom,
                'prenom_arab' => $apprenant->prenom_arab,
                'nom_arab' => $apprenant->nom_arab,
                'tele_num' => $apprenant->tele_num,
                'profile_image' => $apprenant->profile_image,
                'matricule' => $apprenant->matricule,
                'sexe' => $apprenant->sexe,
                'actif' => $apprenant->actif,
                'diplome' => $apprenant->diplome,
                'date_naissance' => $apprenant->date_naissance,
                'date_inscription' => $apprenant->date_inscription,
                'lieu_naissance' => $apprenant->lieu_naissance,
                'cin' => $apprenant->cin,
                'adresse' => $apprenant->adresse,
                'niveaux_scolaire_id' => $apprenant->niveaux_scolaire_id,
                'nationalite_id' => $apprenant->nationalite_id,
                'user_id' => $apprenant->user_id,
                'reference' => $apprenant->reference,
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Appliquer les bordures à toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Appliquer un style spécifique aux en-têtes (ligne 1)
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'], // Texte blanc
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'], // Fond bleu
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Ajuster automatiquement la largeur des colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
