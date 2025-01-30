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
            'matricule',
            'nom',
            'prenom',
            'prenom_arab',
            'nom_arab',
            'tele_num',
            'adresse',
            'diplome',
            'echelle',
            'echelon',
            'profile_image',
            'user_id',
            'reference',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($formateur) {
            return [
                'matricule' => $formateur->matricule,
                'nom' => $formateur->nom,
                'prenom' => $formateur->prenom,
                'prenom_arab' => $formateur->prenom_arab,
                'nom_arab' => $formateur->nom_arab,
                'tele_num' => $formateur->tele_num,
                'adresse' => $formateur->adresse,
                'diplome' => $formateur->diplome,
                'echelle' => $formateur->echelle,
                'echelon' => $formateur->echelon,
                'profile_image' => $formateur->profile_image,
                'user_id' => $formateur->user_id,
                'reference' => $formateur->reference,
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
