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
            'nom',
            'prenom',
            'prenom_arab',
            'nom_arab',
            'tele_num',
            'profile_image',
            'matricule',
            'sexe',
            'actif',
            'diplome',
            'date_naissance',
            'date_inscription',
            'lieu_naissance',
            'cin',
            'adresse',
            'niveaux_scolaire_id',
            'nationalite_id',
            'user_id',
            'reference',
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
