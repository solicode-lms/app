<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Exports\Base;

use Modules\PkgUtilisateurs\Models\Apprenant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

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
            'actif',
            'adresse',
            'cin',
            'date_inscription',
            'date_naissance',
            'diplome',
            'groupe_id',
            'lieu_naissance',
            'matricule',
            'nationalite_id',
            'niveaux_scolaire_id',
            'nom',
            'nom_arab',
            'prenom',
            'prenom_arab',
            'profile_image',
            'sexe',
            'tele_num',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($apprenant) {
            return [
                'actif' => $apprenant->actif,
                'adresse' => $apprenant->adresse,
                'cin' => $apprenant->cin,
                'date_inscription' => $apprenant->date_inscription,
                'date_naissance' => $apprenant->date_naissance,
                'diplome' => $apprenant->diplome,
                'groupe_id' => $apprenant->groupe_id,
                'lieu_naissance' => $apprenant->lieu_naissance,
                'matricule' => $apprenant->matricule,
                'nationalite_id' => $apprenant->nationalite_id,
                'niveaux_scolaire_id' => $apprenant->niveaux_scolaire_id,
                'nom' => $apprenant->nom,
                'nom_arab' => $apprenant->nom_arab,
                'prenom' => $apprenant->prenom,
                'prenom_arab' => $apprenant->prenom_arab,
                'profile_image' => $apprenant->profile_image,
                'sexe' => $apprenant->sexe,
                'tele_num' => $apprenant->tele_num,
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
