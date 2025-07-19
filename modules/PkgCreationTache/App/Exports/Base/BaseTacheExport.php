<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgCreationTache\App\Exports\Base;

use Modules\PkgCreationTache\Models\Tache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $format;

    public function __construct($data, $format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    /**
     * Génère les en-têtes du fichier exporté
     */
    public function headings(): array
    {
        if ($this->format === 'csv') {
            return [
                'ordre' => 'ordre',
                'titre' => 'titre',
                'priorite_tache_reference' => 'priorite_tache_reference',
                'projet_reference' => 'projet_reference',
                'description' => 'description',
                'dateDebut' => 'dateDebut',
                'dateFin' => 'dateFin',
                'reference' => 'reference',
                'note' => 'note',
            ];
        } else {
            return [
                'ordre' => __('PkgCreationTache::tache.ordre'),
                'titre' => __('PkgCreationTache::tache.titre'),
                'priorite_tache_reference' => __('PkgCreationTache::tache.priorite_tache_reference'),
                'projet_reference' => __('PkgCreationTache::tache.projet_reference'),
                'description' => __('PkgCreationTache::tache.description'),
                'dateDebut' => __('PkgCreationTache::tache.dateDebut'),
                'dateFin' => __('PkgCreationTache::tache.dateFin'),
                'reference' => __('Core::msg.reference'),
                'note' => __('PkgCreationTache::tache.note'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($tache) {
            return [
                'ordre' => $tache->ordre,
                'titre' => $tache->titre,
                'priorite_tache_reference' => $tache->prioriteTache?->reference,
                'projet_reference' => $tache->projet?->reference,
                'description' => $tache->description,
                'dateDebut' => $tache->dateDebut,
                'dateFin' => $tache->dateFin,
                'reference' => $tache->reference,
                'note' => $tache->note,
            ];
        });
    }

    /**
     * Applique le style au fichier exporté
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Bordures pour toutes les cellules contenant des données
        $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        // Style spécifique pour les en-têtes
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Largeur automatique pour toutes les colonnes
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
