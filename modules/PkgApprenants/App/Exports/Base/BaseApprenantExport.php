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
            'prenom' => 'prenom',
            'prenom_arab' => 'prenom_arab',
            'nom_arab' => 'nom_arab',
            'profile_image' => 'profile_image',
            'sexe' => 'sexe',
            'tele_num' => 'tele_num',
            'diplome' => 'diplome',
            'date_naissance' => 'date_naissance',
            'lieu_naissance' => 'lieu_naissance',
            'cin' => 'cin',
            'adresse' => 'adresse',
            'niveaux_scolaire_id' => 'niveaux_scolaire_id',
            'matricule' => 'matricule',
            'nationalite_id' => 'nationalite_id',
            'actif' => 'actif',
            'date_inscription' => 'date_inscription',
            'reference' => 'reference',
            'user_id' => 'user_id',
        ];
        }else{
        return [
            'nom' => __('PkgApprenants::apprenant.nom'),
            'prenom' => __('PkgApprenants::apprenant.prenom'),
            'prenom_arab' => __('PkgApprenants::apprenant.prenom_arab'),
            'nom_arab' => __('PkgApprenants::apprenant.nom_arab'),
            'profile_image' => __('PkgApprenants::apprenant.profile_image'),
            'sexe' => __('PkgApprenants::apprenant.sexe'),
            'tele_num' => __('PkgApprenants::apprenant.tele_num'),
            'diplome' => __('PkgApprenants::apprenant.diplome'),
            'date_naissance' => __('PkgApprenants::apprenant.date_naissance'),
            'lieu_naissance' => __('PkgApprenants::apprenant.lieu_naissance'),
            'cin' => __('PkgApprenants::apprenant.cin'),
            'adresse' => __('PkgApprenants::apprenant.adresse'),
            'niveaux_scolaire_id' => __('PkgApprenants::apprenant.niveaux_scolaire_id'),
            'matricule' => __('PkgApprenants::apprenant.matricule'),
            'nationalite_id' => __('PkgApprenants::apprenant.nationalite_id'),
            'actif' => __('PkgApprenants::apprenant.actif'),
            'date_inscription' => __('PkgApprenants::apprenant.date_inscription'),
            'reference' => __('Core::msg.reference'),
            'user_id' => __('PkgApprenants::apprenant.user_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($apprenant) {
            return [
                'nom' => $apprenant->nom,
                'prenom' => $apprenant->prenom,
                'prenom_arab' => $apprenant->prenom_arab,
                'nom_arab' => $apprenant->nom_arab,
                'profile_image' => $apprenant->profile_image,
                'sexe' => $apprenant->sexe,
                'tele_num' => $apprenant->tele_num,
                'diplome' => $apprenant->diplome,
                'date_naissance' => $apprenant->date_naissance,
                'lieu_naissance' => $apprenant->lieu_naissance,
                'cin' => $apprenant->cin,
                'adresse' => $apprenant->adresse,
                'niveaux_scolaire_id' => $apprenant->niveaux_scolaire_id,
                'matricule' => $apprenant->matricule,
                'nationalite_id' => $apprenant->nationalite_id,
                'actif' => $apprenant->actif,
                'date_inscription' => $apprenant->date_inscription,
                'reference' => $apprenant->reference,
                'user_id' => $apprenant->user_id,
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
