<?php
// Ce fichier est maintenu par ESSARRAJ Fouad



namespace Modules\PkgRealisationTache\App\Exports\Base;

use Modules\PkgRealisationTache\Models\CommentaireRealisationTache;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class BaseCommentaireRealisationTacheExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;

    public function __construct($data,$format)
    {
        $this->data = $data;
        $this->format = $format;
    }

    public function headings(): array
    {
     if($this->format == 'csv'){
        return [
            'commentaire' => 'commentaire',
            'dateCommentaire' => 'dateCommentaire',
            'reference' => 'reference',
            'realisation_tache_id' => 'realisation_tache_id',
            'formateur_id' => 'formateur_id',
            'apprenant_id' => 'apprenant_id',
        ];
        }else{
        return [
            'commentaire' => __('PkgRealisationTache::commentaireRealisationTache.commentaire'),
            'dateCommentaire' => __('PkgRealisationTache::commentaireRealisationTache.dateCommentaire'),
            'reference' => __('Core::msg.reference'),
            'realisation_tache_id' => __('PkgRealisationTache::commentaireRealisationTache.realisation_tache_id'),
            'formateur_id' => __('PkgRealisationTache::commentaireRealisationTache.formateur_id'),
            'apprenant_id' => __('PkgRealisationTache::commentaireRealisationTache.apprenant_id'),
        ];

        }
   
    }

    public function collection()
    {
        return $this->data->map(function ($commentaireRealisationTache) {
            return [
                'commentaire' => $commentaireRealisationTache->commentaire,
                'dateCommentaire' => $commentaireRealisationTache->dateCommentaire,
                'reference' => $commentaireRealisationTache->reference,
                'realisation_tache_id' => $commentaireRealisationTache->realisation_tache_id,
                'formateur_id' => $commentaireRealisationTache->formateur_id,
                'apprenant_id' => $commentaireRealisationTache->apprenant_id,
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
