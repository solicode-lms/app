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
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseProjetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'titre' => 'titre',
                'travail_a_faire' => 'travail_a_faire',
                'critere_de_travail' => 'critere_de_travail',
                'nombre_jour' => 'nombre_jour',
                'filiere_reference' => 'filiere_reference',
                'formateur_reference' => 'formateur_reference',
                'description' => 'description',
                'reference' => 'reference',
            ];
        } else {
            return [
                'titre' => __('PkgCreationProjet::projet.titre'),
                'travail_a_faire' => __('PkgCreationProjet::projet.travail_a_faire'),
                'critere_de_travail' => __('PkgCreationProjet::projet.critere_de_travail'),
                'nombre_jour' => __('PkgCreationProjet::projet.nombre_jour'),
                'filiere_reference' => __('PkgCreationProjet::projet.filiere_reference'),
                'formateur_reference' => __('PkgCreationProjet::projet.formateur_reference'),
                'description' => __('PkgCreationProjet::projet.description'),
                'reference' => __('Core::msg.reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($projet) {
            return [
                'titre' => $projet->titre,
                'travail_a_faire' => $projet->travail_a_faire,
                'critere_de_travail' => $projet->critere_de_travail,
                'nombre_jour' => (string) $projet->nombre_jour,
                'filiere_reference' => $projet->filiere?->reference,
                'formateur_reference' => $projet->formateur?->reference,
                'description' => $projet->description,
                'reference' => $projet->reference,
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
