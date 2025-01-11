<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgUtilisateurs\App\Exports\Base;

use Modules\PkgUtilisateurs\Models\Formateur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseFormateurExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'adresse',
            'diplome',
            'echelle',
            'echelon',
            'matricule',
            'nom',
            'nom_arab',
            'prenom',
            'prenom_arab',
            'profile_image',
            'tele_num',
            'user_id',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($formateur) {
            return [
                'adresse' => $formateur->adresse,
                'diplome' => $formateur->diplome,
                'echelle' => $formateur->echelle,
                'echelon' => $formateur->echelon,
                'matricule' => $formateur->matricule,
                'nom' => $formateur->nom,
                'nom_arab' => $formateur->nom_arab,
                'prenom' => $formateur->prenom,
                'prenom_arab' => $formateur->prenom_arab,
                'profile_image' => $formateur->profile_image,
                'tele_num' => $formateur->tele_num,
                'user_id' => $formateur->user_id,
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
