<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Exports\Base;

use Modules\PkgCreationProjet\Models\Projet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'titre',
            'travail_a_faire',
            'critere_de_travail',
            'nombre_jour',
            'description',
            'reference',
            'formateur_id',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($projet) {
            return [
                'titre' => $projet->titre,
                'travail_a_faire' => $projet->travail_a_faire,
                'critere_de_travail' => $projet->critere_de_travail,
                'nombre_jour' => $projet->nombre_jour,
                'description' => $projet->description,
                'reference' => $projet->reference,
                'formateur_id' => $projet->formateur_id,
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
