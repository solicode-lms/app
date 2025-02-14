<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgApprenants\App\Exports\Base;

use Modules\PkgApprenants\Models\ApprenantKonosy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseApprenantKonosyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
            'MatriculeEtudiant' => 'MatriculeEtudiant',
            'Nom' => 'Nom',
            'Prenom' => 'Prenom',
            'Sexe' => 'Sexe',
            'EtudiantActif' => 'EtudiantActif',
            'Diplome' => 'Diplome',
            'Principale' => 'Principale',
            'LibelleLong' => 'LibelleLong',
            'CodeDiplome' => 'CodeDiplome',
            'DateNaissance' => 'DateNaissance',
            'DateInscription' => 'DateInscription',
            'LieuNaissance' => 'LieuNaissance',
            'CIN' => 'CIN',
            'NTelephone' => 'NTelephone',
            'Adresse' => 'Adresse',
            'Nationalite' => 'Nationalite',
            'Nom_Arabe' => 'Nom_Arabe',
            'Prenom_Arabe' => 'Prenom_Arabe',
            'NiveauScolaire' => 'NiveauScolaire',
            'reference' => 'reference',
        ];
        }else{
        return [
            'MatriculeEtudiant' => __('PkgApprenants::apprenantKonosy.MatriculeEtudiant'),
            'Nom' => __('PkgApprenants::apprenantKonosy.Nom'),
            'Prenom' => __('PkgApprenants::apprenantKonosy.Prenom'),
            'Sexe' => __('PkgApprenants::apprenantKonosy.Sexe'),
            'EtudiantActif' => __('PkgApprenants::apprenantKonosy.EtudiantActif'),
            'Diplome' => __('PkgApprenants::apprenantKonosy.Diplome'),
            'Principale' => __('PkgApprenants::apprenantKonosy.Principale'),
            'LibelleLong' => __('PkgApprenants::apprenantKonosy.LibelleLong'),
            'CodeDiplome' => __('PkgApprenants::apprenantKonosy.CodeDiplome'),
            'DateNaissance' => __('PkgApprenants::apprenantKonosy.DateNaissance'),
            'DateInscription' => __('PkgApprenants::apprenantKonosy.DateInscription'),
            'LieuNaissance' => __('PkgApprenants::apprenantKonosy.LieuNaissance'),
            'CIN' => __('PkgApprenants::apprenantKonosy.CIN'),
            'NTelephone' => __('PkgApprenants::apprenantKonosy.NTelephone'),
            'Adresse' => __('PkgApprenants::apprenantKonosy.Adresse'),
            'Nationalite' => __('PkgApprenants::apprenantKonosy.Nationalite'),
            'Nom_Arabe' => __('PkgApprenants::apprenantKonosy.Nom_Arabe'),
            'Prenom_Arabe' => __('PkgApprenants::apprenantKonosy.Prenom_Arabe'),
            'NiveauScolaire' => __('PkgApprenants::apprenantKonosy.NiveauScolaire'),
            'reference' => __('Core::msg.reference'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($apprenantKonosy) {
            return [
                'MatriculeEtudiant' => $apprenantKonosy->MatriculeEtudiant,
                'Nom' => $apprenantKonosy->Nom,
                'Prenom' => $apprenantKonosy->Prenom,
                'Sexe' => $apprenantKonosy->Sexe,
                'EtudiantActif' => $apprenantKonosy->EtudiantActif,
                'Diplome' => $apprenantKonosy->Diplome,
                'Principale' => $apprenantKonosy->Principale,
                'LibelleLong' => $apprenantKonosy->LibelleLong,
                'CodeDiplome' => $apprenantKonosy->CodeDiplome,
                'DateNaissance' => $apprenantKonosy->DateNaissance,
                'DateInscription' => $apprenantKonosy->DateInscription,
                'LieuNaissance' => $apprenantKonosy->LieuNaissance,
                'CIN' => $apprenantKonosy->CIN,
                'NTelephone' => $apprenantKonosy->NTelephone,
                'Adresse' => $apprenantKonosy->Adresse,
                'Nationalite' => $apprenantKonosy->Nationalite,
                'Nom_Arabe' => $apprenantKonosy->Nom_Arabe,
                'Prenom_Arabe' => $apprenantKonosy->Prenom_Arabe,
                'NiveauScolaire' => $apprenantKonosy->NiveauScolaire,
                'reference' => $apprenantKonosy->reference,
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
