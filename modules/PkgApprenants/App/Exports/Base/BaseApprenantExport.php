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
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseApprenantExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
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
                'nom' => 'nom',
                'nom_arab' => 'nom_arab',
                'prenom' => 'prenom',
                'prenom_arab' => 'prenom_arab',
                'profile_image' => 'profile_image',
                'cin' => 'cin',
                'date_naissance' => 'date_naissance',
                'sexe' => 'sexe',
                'nationalite_reference' => 'nationalite_reference',
                'lieu_naissance' => 'lieu_naissance',
                'diplome' => 'diplome',
                'adresse' => 'adresse',
                'niveaux_scolaire_reference' => 'niveaux_scolaire_reference',
                'tele_num' => 'tele_num',
                'user_reference' => 'user_reference',
                'reference' => 'reference',
                'sousGroupes' => 'sousGroupes',
                'matricule' => 'matricule',
                'groupes' => 'groupes',
                'date_inscription' => 'date_inscription',
                'actif' => 'actif',
            ];
        } else {
            return [
                'nom' => __('PkgApprenants::apprenant.nom'),
                'nom_arab' => __('PkgApprenants::apprenant.nom_arab'),
                'prenom' => __('PkgApprenants::apprenant.prenom'),
                'prenom_arab' => __('PkgApprenants::apprenant.prenom_arab'),
                'profile_image' => __('PkgApprenants::apprenant.profile_image'),
                'cin' => __('PkgApprenants::apprenant.cin'),
                'date_naissance' => __('PkgApprenants::apprenant.date_naissance'),
                'sexe' => __('PkgApprenants::apprenant.sexe'),
                'nationalite_reference' => __('PkgApprenants::nationalite.singular'),
                'lieu_naissance' => __('PkgApprenants::apprenant.lieu_naissance'),
                'diplome' => __('PkgApprenants::apprenant.diplome'),
                'adresse' => __('PkgApprenants::apprenant.adresse'),
                'niveaux_scolaire_reference' => __('PkgApprenants::niveauxScolaire.singular'),
                'tele_num' => __('PkgApprenants::apprenant.tele_num'),
                'user_reference' => __('PkgAutorisation::user.singular'),
                'reference' => __('Core::msg.reference'),
                    'sousGroupes' => __('PkgApprenants::sousGroupe.plural'),
                'matricule' => __('PkgApprenants::apprenant.matricule'),
                    'groupes' => __('PkgApprenants::groupe.plural'),
                'date_inscription' => __('PkgApprenants::apprenant.date_inscription'),
                'actif' => __('PkgApprenants::apprenant.actif'),
            ];
        }
    }

    /**
     * Prépare les données à exporter
     */
    public function collection()
    {
        return $this->data->map(function ($apprenant) {
            return [
                'nom' => $apprenant->nom,
                'nom_arab' => $apprenant->nom_arab,
                'prenom' => $apprenant->prenom,
                'prenom_arab' => $apprenant->prenom_arab,
                'profile_image' => $apprenant->profile_image,
                'cin' => $apprenant->cin,
                'date_naissance' => $apprenant->date_naissance,
                'sexe' => $apprenant->sexe,
                'nationalite_reference' => $apprenant->nationalite?->reference,
                'lieu_naissance' => $apprenant->lieu_naissance,
                'diplome' => $apprenant->diplome,
                'adresse' => $apprenant->adresse,
                'niveaux_scolaire_reference' => $apprenant->niveauxScolaire?->reference,
                'tele_num' => $apprenant->tele_num,
                'user_reference' => $apprenant->user?->reference,
                'reference' => $apprenant->reference,
                'sousGroupes' => $apprenant->sousGroupes
                    ->pluck('reference')
                    ->implode('|'),
                'matricule' => $apprenant->matricule,
                'groupes' => $apprenant->groupes
                    ->pluck('reference')
                    ->implode('|'),
                'date_inscription' => $apprenant->date_inscription,
                'actif' => $apprenant->actif ? '1' : '0',
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
