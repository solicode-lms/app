<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationProjets\App\Exports\Base;

use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BaseRealisationProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'date_debut',
            'date_fin',
            'rapport',
            'projet_id',
            'etats_realisation_projet_id',
            'apprenant_id',
            'affectation_projet_id',
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($realisationProjet) {
            return [
                'date_debut' => $realisationProjet->date_debut,
                'date_fin' => $realisationProjet->date_fin,
                'rapport' => $realisationProjet->rapport,
                'projet_id' => $realisationProjet->projet_id,
                'etats_realisation_projet_id' => $realisationProjet->etats_realisation_projet_id,
                'apprenant_id' => $realisationProjet->apprenant_id,
                'affectation_projet_id' => $realisationProjet->affectation_projet_id,
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
