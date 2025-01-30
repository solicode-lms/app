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
            'MatriculeEtudiant',
            'Nom',
            'Prenom',
            'Sexe',
            'EtudiantActif',
            'Diplome',
            'Principale',
            'LibelleLong',
            'CodeDiplome',
            'DateNaissance',
            'DateInscription',
            'LieuNaissance',
            'CIN',
            'NTelephone',
            'Adresse',
            'Nationalite',
            'Nom_Arabe',
            'Prenom_Arabe',
            'NiveauScolaire',
            'reference',
        ];
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
