<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgSessions\App\Exports\Base;

use Modules\PkgSessions\Models\SessionFormation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseSessionFormationExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'reference' => 'reference',
                'titre' => 'titre',
                'date_debut' => 'date_debut',
                'date_fin' => 'date_fin',
                'jour_feries_vacances' => 'jour_feries_vacances',
                'thematique' => 'thematique',
                'objectifs_pedagogique' => 'objectifs_pedagogique',
                'remarques' => 'remarques',
                'titre_prototype' => 'titre_prototype',
                'description_prototype' => 'description_prototype',
                'contraintes_prototype' => 'contraintes_prototype',
                'titre_projet' => 'titre_projet',
                'description_projet' => 'description_projet',
                'contraintes_projet' => 'contraintes_projet',
                'filiere_reference' => 'filiere_reference',
                'annee_formation_reference' => 'annee_formation_reference',
            ];
        } else {
            return [
                'ordre' => __('PkgSessions::sessionFormation.ordre'),
                'reference' => __('Core::msg.reference'),
                'titre' => __('PkgSessions::sessionFormation.titre'),
                'date_debut' => __('PkgSessions::sessionFormation.date_debut'),
                'date_fin' => __('PkgSessions::sessionFormation.date_fin'),
                'jour_feries_vacances' => __('PkgSessions::sessionFormation.jour_feries_vacances'),
                'thematique' => __('PkgSessions::sessionFormation.thematique'),
                'objectifs_pedagogique' => __('PkgSessions::sessionFormation.objectifs_pedagogique'),
                'remarques' => __('PkgSessions::sessionFormation.remarques'),
                'titre_prototype' => __('PkgSessions::sessionFormation.titre_prototype'),
                'description_prototype' => __('PkgSessions::sessionFormation.description_prototype'),
                'contraintes_prototype' => __('PkgSessions::sessionFormation.contraintes_prototype'),
                'titre_projet' => __('PkgSessions::sessionFormation.titre_projet'),
                'description_projet' => __('PkgSessions::sessionFormation.description_projet'),
                'contraintes_projet' => __('PkgSessions::sessionFormation.contraintes_projet'),
                'filiere_reference' => __('PkgSessions::sessionFormation.filiere_reference'),
                'annee_formation_reference' => __('PkgSessions::sessionFormation.annee_formation_reference'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($sessionFormation) {
            return [
                'ordre' => (string) $sessionFormation->ordre,
                'reference' => $sessionFormation->reference,
                'titre' => $sessionFormation->titre,
                'date_debut' => $sessionFormation->date_debut,
                'date_fin' => $sessionFormation->date_fin,
                'jour_feries_vacances' => $sessionFormation->jour_feries_vacances,
                'thematique' => $sessionFormation->thematique,
                'objectifs_pedagogique' => $sessionFormation->objectifs_pedagogique,
                'remarques' => $sessionFormation->remarques,
                'titre_prototype' => $sessionFormation->titre_prototype,
                'description_prototype' => $sessionFormation->description_prototype,
                'contraintes_prototype' => $sessionFormation->contraintes_prototype,
                'titre_projet' => $sessionFormation->titre_projet,
                'description_projet' => $sessionFormation->description_projet,
                'contraintes_projet' => $sessionFormation->contraintes_projet,
                'filiere_reference' => $sessionFormation->filiere?->reference,
                'annee_formation_reference' => $sessionFormation->anneeFormation?->reference,
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
