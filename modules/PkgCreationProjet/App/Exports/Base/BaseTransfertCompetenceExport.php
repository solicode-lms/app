<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationProjet\App\Exports\Base;

use Modules\PkgCreationProjet\Models\TransfertCompetence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseTransfertCompetenceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        return [
            'competence_id' => __('PkgCreationProjet::transfertCompetence.competence_id'),
            'question' => __('PkgCreationProjet::transfertCompetence.question'),
            'niveau_difficulte_id' => __('PkgCreationProjet::transfertCompetence.niveau_difficulte_id'),
            'note' => __('PkgCreationProjet::transfertCompetence.note'),
            'reference' => __('Core::msg.reference'),
            'projet_id' => __('PkgCreationProjet::transfertCompetence.projet_id'),
        ];
    }

    public function collection()
    {
        return $this->data->map(function ($transfertCompetence) {
            return [
                'competence_id' => $transfertCompetence->competence_id,
                'question' => $transfertCompetence->question,
                'niveau_difficulte_id' => $transfertCompetence->niveau_difficulte_id,
                'note' => $transfertCompetence->note,
                'reference' => $transfertCompetence->reference,
                'projet_id' => $transfertCompetence->projet_id,
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
