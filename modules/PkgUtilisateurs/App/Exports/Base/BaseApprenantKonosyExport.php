<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Exports\Base;

use Modules\PkgUtilisateurs\Models\ApprenantKonosy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseApprenantKonosyExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'Adresse',
            'CIN',
            'CodeDiplome',
            'DateInscription',
            'DateNaissance',
            'Diplome',
            'EtudiantActif',
            'LibelleLong',
            'LieuNaissance',
            'MatriculeEtudiant',
            'Nationalite',
            'NiveauScolaire',
            'Nom',
            'Nom_Arabe',
            'NTelephone',
            'Prenom',
            'Prenom_Arabe',
            'Principale',
            'Sexe',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($apprenantKonosy) {
            return [
                'Adresse' => $apprenantKonosy->Adresse,
                'CIN' => $apprenantKonosy->CIN,
                'CodeDiplome' => $apprenantKonosy->CodeDiplome,
                'DateInscription' => $apprenantKonosy->DateInscription,
                'DateNaissance' => $apprenantKonosy->DateNaissance,
                'Diplome' => $apprenantKonosy->Diplome,
                'EtudiantActif' => $apprenantKonosy->EtudiantActif,
                'LibelleLong' => $apprenantKonosy->LibelleLong,
                'LieuNaissance' => $apprenantKonosy->LieuNaissance,
                'MatriculeEtudiant' => $apprenantKonosy->MatriculeEtudiant,
                'Nationalite' => $apprenantKonosy->Nationalite,
                'NiveauScolaire' => $apprenantKonosy->NiveauScolaire,
                'Nom' => $apprenantKonosy->Nom,
                'Nom_Arabe' => $apprenantKonosy->Nom_Arabe,
                'NTelephone' => $apprenantKonosy->NTelephone,
                'Prenom' => $apprenantKonosy->Prenom,
                'Prenom_Arabe' => $apprenantKonosy->Prenom_Arabe,
                'Principale' => $apprenantKonosy->Principale,
                'Sexe' => $apprenantKonosy->Sexe,
            ];
        });
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A1:Z{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A1:Z1")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFD3D3D3',
                ],
            ],
        ]);
    }
}
