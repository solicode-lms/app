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

    public function __construct($data,$format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    public function headings(): array
    {
     if($this->format == 'csv'){
        return [
            'nom' => 'nom',
            'nom_arab' => 'nom_arab',
            'prenom' => 'prenom',
            'prenom_arab' => 'prenom_arab',
            'profile_image' => 'profile_image',
            'cin' => 'cin',
            'date_naissance' => 'date_naissance',
            'sexe' => 'sexe',
            'nationalite_id' => 'nationalite_id',
            'lieu_naissance' => 'lieu_naissance',
            'diplome' => 'diplome',
            'adresse' => 'adresse',
            'niveaux_scolaire_id' => 'niveaux_scolaire_id',
            'tele_num' => 'tele_num',
            'user_id' => 'user_id',
            'reference' => 'reference',
            'matricule' => 'matricule',
            'date_inscription' => 'date_inscription',
            'actif' => 'actif',
        ];
        }else{
        return [
            'nom' => __('PkgApprenants::apprenant.nom'),
            'nom_arab' => __('PkgApprenants::apprenant.nom_arab'),
            'prenom' => __('PkgApprenants::apprenant.prenom'),
            'prenom_arab' => __('PkgApprenants::apprenant.prenom_arab'),
            'profile_image' => __('PkgApprenants::apprenant.profile_image'),
            'cin' => __('PkgApprenants::apprenant.cin'),
            'date_naissance' => __('PkgApprenants::apprenant.date_naissance'),
            'sexe' => __('PkgApprenants::apprenant.sexe'),
            'nationalite_id' => __('PkgApprenants::apprenant.nationalite_id'),
            'lieu_naissance' => __('PkgApprenants::apprenant.lieu_naissance'),
            'diplome' => __('PkgApprenants::apprenant.diplome'),
            'adresse' => __('PkgApprenants::apprenant.adresse'),
            'niveaux_scolaire_id' => __('PkgApprenants::apprenant.niveaux_scolaire_id'),
            'tele_num' => __('PkgApprenants::apprenant.tele_num'),
            'user_id' => __('PkgApprenants::apprenant.user_id'),
            'reference' => __('Core::msg.reference'),
            'matricule' => __('PkgApprenants::apprenant.matricule'),
            'date_inscription' => __('PkgApprenants::apprenant.date_inscription'),
            'actif' => __('PkgApprenants::apprenant.actif'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($apprenant) {
            return [
                'nom' => $apprenant->nom,
                'nom_arab' => $apprenant->nom_arab,
                'prenom' => $apprenant->prenom,
                'prenom_arab' => $apprenant->prenom_arab,
                'profile_image' => $apprenant->profile_image,
                'cin' => $apprenant->cin,
                'date_naissance' => $apprenant->date_naissance,
                'sexe' => $apprenant->sexe,
                'nationalite_id' => $apprenant->nationalite_id,
                'lieu_naissance' => $apprenant->lieu_naissance,
                'diplome' => $apprenant->diplome,
                'adresse' => $apprenant->adresse,
                'niveaux_scolaire_id' => $apprenant->niveaux_scolaire_id,
                'tele_num' => $apprenant->tele_num,
                'user_id' => $apprenant->user_id,
                'reference' => $apprenant->reference,
                'matricule' => $apprenant->matricule,
                'date_inscription' => $apprenant->date_inscription,
                'actif' => $apprenant->actif,
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
