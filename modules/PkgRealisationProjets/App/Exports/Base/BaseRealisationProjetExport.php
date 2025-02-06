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
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
            'date_debut' => __('PkgRealisationProjets::realisationProjet.date_debut'),
            'date_fin' => __('PkgRealisationProjets::realisationProjet.date_fin'),
            'rapport' => __('PkgRealisationProjets::realisationProjet.rapport'),
            'etats_realisation_projet_id' => __('PkgRealisationProjets::realisationProjet.etats_realisation_projet_id'),
            'apprenant_id' => __('PkgRealisationProjets::realisationProjet.apprenant_id'),
            'affectation_projet_id' => __('PkgRealisationProjets::realisationProjet.affectation_projet_id'),
            'reference' => __('Core::msg.reference'),
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($realisationProjet) {
            return [
                'date_debut' => $realisationProjet->date_debut,
                'date_fin' => $realisationProjet->date_fin,
                'rapport' => $realisationProjet->rapport,
                'etats_realisation_projet_id' => $realisationProjet->etats_realisation_projet_id,
                'apprenant_id' => $realisationProjet->apprenant_id,
                'affectation_projet_id' => $realisationProjet->affectation_projet_id,
                'reference' => $realisationProjet->reference,
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
